<?php

namespace Modules\Donate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Donate\Services\GatewayManager;
use Modules\Donate\Domain\Models\DonationTransaction;
use Illuminate\Support\Facades\DB;
use Modules\Donate\Domain\Models\DonationPlan;

class DonateController extends Controller
{
    public function index(GatewayManager $gateways)
    {
        $available = array_map(fn($g) => ['id' => $g->id(), 'name' => $g->displayName()], $gateways->all());
        $plans = DonationPlan::where('active', true)->orderBy('sort_order')->get();
        
        return view('donate::home', ['gateways' => $available, 'plans' => $plans]); 
    }

    public function checkout(Request $request, GatewayManager $gateways)
    {
        $gatewayId = $request->string('gateway')->toString();
        
        if ($request->filled('plan_id')) {
            $plan = DonationPlan::findOrFail($request->input('plan_id'));
            $amount = $plan->amount;
            $dpAmount = $plan->dp_total;
        } else {
            $amount = $request->input('amount', 0);
            $rate = (int) config('donate.dp_rate', 100);
            $dpAmount = (int) (($request->input('amount', 0)) * $rate);
        }
        
        $gateway = $gateways->get($gatewayId);
        abort_unless($gateway, 404);
        
        $meta = ['user_id' => optional($request->user())->id];
        
        if ($request->filled('nonce')) {
            $meta['nonce'] = $request->input('nonce');
        }
        
        $result = $gateway->createCheckout($amount, $meta);
        
        if (isset($result['client_token'])) {
            return view('donate::braintree', ['token' => $result['client_token'], 'amount' => $amount]);
        }
        
        if (isset($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }
        
        if (($result['status'] ?? null) === 'success') {
            $user = $request->user();
            $txId = null;
            
            DB::transaction(function () use ($user, $gatewayId, $amount, $dpAmount, $result, &$txId) {
                $tx = DonationTransaction::create([
                    'user_id' => $user->id,
                    'gateway' => $gatewayId,
                    'transaction_id' => $result['transaction_id'] ?? null,
                    'amount' => $amount,
                    'currency' => 'USD',
                    'dp_awarded' => $dpAmount,
                    'status' => 'completed',
                    'meta' => ['raw' => $result],
                ]);
                $user->increment('dp', $dpAmount);
                $txId = $tx->id;
            });
            
            return redirect()->route('donate.receipt', ['id' => $txId]);
        }
        
        return response()->json($result, 400);
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
}
