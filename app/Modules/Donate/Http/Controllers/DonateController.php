<?php

namespace Modules\Donate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Donate\Services\GatewayManager;
use Modules\Donate\Domain\Models\DonationTransaction;
use Modules\Donate\Domain\Models\DonationPlan;
use Modules\Store\Domain\Models\StoreProduct;

class DonateController extends Controller
{
    public function index(GatewayManager $gateways)
    {
        $available = array_map(fn($g) => ['id' => $g->id(), 'name' => $g->displayName()], $gateways->all());
        $plans = DonationPlan::query()
            ->where('active', true)
            ->select(['id', 'name', 'amount', 'dp_base', 'sort_order'])
            ->orderBy('sort_order')
            ->get();

        $storeProducts = StoreProduct::query()
            ->where('active', true)
            ->select(['id', 'name', 'description', 'cost', 'type', 'sort_order'])
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('donate::home', [
            'gateways'      => $available,
            'plans'         => $plans,
            'storeProducts' => $storeProducts,
        ]);
    }

    /**
     * Handle a donation checkout request.
     *
     * Authorization: the user must be authenticated.
     */
    public function checkout(Request $request, GatewayManager $gateways)
    {
        $gatewayId = $request->string('gateway')->toString();

        if ($request->filled('plan_id')) {
            $plan = DonationPlan::findOrFail($request->input('plan_id'));
            $amount = $plan->amount;
            $dpAmount = $plan->dp_base;
        } else {
            $amount = (float) $request->input('amount', 0);
            $rate = (int) config('donate.dp_rate', 100);
            $dpAmount = (int) ($amount * $rate);
        }

        $gateway = $gateways->get($gatewayId);
        abort_unless($gateway, 404);

        $meta = ['user_id' => optional($request->user())->id];

        if ($request->filled('nonce')) {
            $meta['nonce'] = $request->input('nonce');
        }

        // ─── Idempotency: nonce is single-use by Braintree's design ──────
        // If the same nonce arrives twice (double-click, back-button,
        // network retry), short-circuit to the existing transaction's
        // receipt instead of re-submitting to Braintree (which would
        // return "Cannot use a paymentMethodNonce more than once").
        //
        // We use a Cache lock so two concurrent requests with the same
        // nonce can't both reach Braintree — the second one waits on
        // the lock and then reads the cached tx id the first one wrote.
        if (!empty($meta['nonce'])) {
            $nonce    = $meta['nonce'];
            $cacheKey = "donate:nonce:{$nonce}";

            $outcome = Cache::lock($cacheKey, 15)->block(5, function () use ($cacheKey, $gateway, $amount, $meta, $gatewayId, $dpAmount, $request) {
                // Did a previous request already complete this nonce?
                $cached = Cache::get("{$cacheKey}:tx");
                if ($cached) {
                    return ['status' => 'duplicate', 'tx_id' => $cached];
                }

                $result = $gateway->createCheckout($amount, $meta);

                if (($result['status'] ?? null) === 'success') {
                    $txId = $this->persistSuccessfulDonation(
                        $request->user(),
                        $gatewayId,
                        $amount,
                        $dpAmount,
                        $result
                    );

                    if ($txId) {
                        // Remember for 24h — covers retries, back-button,
                        // impatient users. Long enough to outlive any
                        // reasonable duplicate submission.
                        Cache::put("{$cacheKey}:tx", $txId, now()->addDay());
                        return ['status' => 'success', 'tx_id' => $txId];
                    }
                }

                // Gateway failed (or DB write failed). Surface the
                // raw gateway response so the user sees the real error.
                // We deliberately do NOT cache this — a transient card
                // decline with the same nonce is impossible (the nonce
                // is already consumed on the Braintree side), but we
                // also don't want to lock the user out for 24h.
                return ['status' => 'error', 'result' => $result];
            });

            switch ($outcome['status']) {
                case 'duplicate':
                case 'success':
                    return redirect()->route('donate.receipt', ['id' => $outcome['tx_id']]);

                case 'error':
                default:
                    return response()->json(
                        $outcome['result'] ?? ['status' => 'error', 'errors' => ['unknown']],
                        400
                    );
            }
        }

        // ─── First leg: no nonce yet, just return the Drop-in form ──────
        $result = $gateway->createCheckout($amount, $meta);

        if (isset($result['client_token'])) {
            return view('donate::braintree', ['token' => $result['client_token'], 'amount' => $amount]);
        }

        if (isset($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        // Some gateways complete on first call (no Drop-in UI).
        if (($result['status'] ?? null) === 'success') {
            $txId = $this->persistSuccessfulDonation(
                $request->user(),
                $gatewayId,
                $amount,
                $dpAmount,
                $result
            );

            if ($txId) {
                return redirect()->route('donate.receipt', ['id' => $txId]);
            }
        }

        return response()->json($result, 400);
    }

    /**
     * Persist a successful gateway response to the DB and credit the
     * user with DP. Returns the new transaction ID, or null on failure.
     *
     * Kept private so the idempotency wrapper in checkout() can call it
     * from inside the lock without re-implementing the write logic.
     */
    protected function persistSuccessfulDonation(
        $user,
        string $gatewayId,
        float $amount,
        int $dpAmount,
        array $result
    ): ?int {
        if (!$user) {
            Log::error('Donate: successful gateway response but no authenticated user', [
                'gateway'        => $gatewayId,
                'transaction_id' => $result['transaction_id'] ?? null,
            ]);
            return null;
        }

        $txId = null;

        DB::transaction(function () use ($user, $gatewayId, $amount, $dpAmount, $result, &$txId) {
            $tx = DonationTransaction::create([
                'user_id'        => $user->id,
                'gateway'        => $gatewayId,
                'transaction_id' => $result['transaction_id'] ?? null,
                'amount'         => $amount,
                'currency'       => 'USD',
                'dp_awarded'     => $dpAmount,
                'status'         => 'completed',
                'meta'           => ['raw' => $result],
            ]);
            $user->increment('dp', $dpAmount);
            $txId = $tx->id;
        });

        return $txId;
    }

    public function callback(string $gateway, Request $request, GatewayManager $gateways)
    {
        $gw = $gateways->get($gateway);
        abort_unless($gw, 404);
        $result = $gw->handleCallback($request);
        return response()->json($result);
    }

    public function webhook(string $gateway, Request $request, GatewayManager $gateways)
    {
        $gw = $gateways->get($gateway);
        abort_unless($gw, 404);
        $result = $gw->handleWebhook($request);
        return response()->json($result);
    }

    public function receipt(int $id)
    {
        $tx = DonationTransaction::query()
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        return view('donate::receipt', ['tx' => $tx]);
    }

    /**
     * Stream the donation receipt as a PDF download.
     *
     * Authorization: the user must own the transaction. There is no
     * admin override on this route — admins should download via the
     * admin transactions panel if needed.
     *
     * Flow:
     *   1. Resolve the transaction (404 / 403 otherwise).
     *   2. Persist the rendered PDF to storage/app/receipts/ so the
     *      nightly cleanup task can prune old files (see
     *      routes/console.php). The filename is deterministic — same
     *      transaction always maps to the same file — so re-downloads
     *      don't accumulate duplicates.
     *   3. Stream the persisted file as the response. If writing to
     *      disk fails for any reason, we fall back to streaming the
     *      PDF directly from DomPDF without persistence.
     *
     * If the barryvdh/laravel-dompdf package is not installed, we
     * fall back to the HTML receipt so the user always gets *something*
     * useful instead of a 500.
     */
    public function receiptPdf(int $id)
    {
        $tx = DonationTransaction::query()
            ->with('user')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $filename     = 'donation-receipt-' . $tx->id . '.pdf';
        $downloadName = 'donation-receipt-' . $tx->id . '.pdf';

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            // Graceful fallback: log it so the operator notices in prod,
            // and serve the HTML receipt so the user is not stranded.
            Log::warning('barryvdh/laravel-dompdf is not installed; serving HTML receipt instead of PDF.', [
                'transaction_id' => $tx->id,
                'user_id'        => auth()->id(),
            ]);

            // `view(...)` returns Illuminate\View\View which doesn't
            // expose `header()`. Use `response()->view(...)` so the
            // Response builder (which DOES have `header()`) wraps the
            // rendered HTML and we can set Content-Disposition.
            return response()
                ->view('donate::receipt-pdf', ['tx' => $tx])
                ->header(
                    'Content-Disposition',
                    'inline; filename="' . pathinfo($filename, PATHINFO_FILENAME) . '.pdf"'
                );
        }

        $directory = storage_path('app/receipts');
        $path      = $directory . DIRECTORY_SEPARATOR . $filename;

        // Render the PDF. We *always* re-render so any change to the
        // template is picked up immediately; the cost is negligible and
        // donation receipts are infrequent events.
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('donate::receipt-pdf', ['tx' => $tx]);

        // ── Persist to disk (best effort) ───────────────────────────────
        // We intentionally do NOT abort the download if this fails —
        // the user should still get their receipt. The cleanup task
        // will simply find nothing to prune.
        $persisted = false;
        try {
            if (!is_dir($directory) && !@mkdir($directory, 0775, true) && !is_dir($directory)) {
                throw new \RuntimeException("Could not create receipts directory: {$directory}");
            }

            // DomPDF's save() writes to disk; output() returns raw bytes.
            // We use save() so the on-disk artifact is what is streamed.
            $pdf->save($path);
            $persisted = is_file($path) && filesize($path) > 0;
        } catch (\Throwable $e) {
            Log::warning('Receipt PDF could not be persisted to disk; streaming in-memory only.', [
                'transaction_id' => $tx->id,
                'user_id'        => auth()->id(),
                'path'           => $path,
                'error'          => $e->getMessage(),
            ]);
        }

        if ($persisted) {
            return response()
                ->download($path, $downloadName, [
                    'Content-Type'        => 'application/pdf',
                    'Cache-Control'       => 'private, max-age=0, must-revalidate',
                    'X-Content-Type-Options' => 'nosniff',
                ])
                ->deleteFileAfter(false);
        }

        // ── Fallback: stream the PDF without persistence ────────────────
        return $pdf->download($downloadName);
    }
}
