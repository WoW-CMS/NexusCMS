<?php

namespace Modules\Donate\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Donate\Domain\Models\DonationPlan;
use Modules\Donate\Domain\Models\DonationTransaction;

class DonateAdminController extends Controller
{
    // ─── Plans ────────────────────────────────────────────────────────────────

    public function plansIndex(): View
    {
        $plans = DonationPlan::query()->orderBy('sort_order')->get();

        return view('donate-admin::plans.index', compact('plans'));
    }

    public function plansCreate(): View
    {
        return view('donate-admin::plans.create');
    }

    public function plansStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'dp_base'     => ['required', 'integer', 'min:1'],
            'extra_pct'   => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_promo'    => ['boolean'],
            'active'      => ['boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_promo']   = $request->boolean('is_promo');
        $validated['active']     = $request->boolean('active');
        $validated['extra_pct']  = (int) ($validated['extra_pct'] ?? 0);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        DonationPlan::query()->create($validated);

        return redirect()
            ->route('admin.donate.plans.index')
            ->with('success', 'Donation plan created.');
    }

    public function plansEdit(DonationPlan $plan): View
    {
        return view('donate-admin::plans.edit', compact('plan'));
    }

    public function plansUpdate(Request $request, DonationPlan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'dp_base'     => ['required', 'integer', 'min:1'],
            'extra_pct'   => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_promo'    => ['boolean'],
            'active'      => ['boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_promo']   = $request->boolean('is_promo');
        $validated['active']     = $request->boolean('active');
        $validated['extra_pct']  = (int) ($validated['extra_pct'] ?? 0);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $plan->update($validated);

        return redirect()
            ->route('admin.donate.plans.index')
            ->with('success', 'Donation plan updated.');
    }

    public function plansDestroy(DonationPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()
            ->route('admin.donate.plans.index')
            ->with('success', 'Donation plan deleted.');
    }

    // ─── Transactions ─────────────────────────────────────────────────────────

    public function transactionsIndex(): View
    {
        $transactions = DonationTransaction::query()
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('donate-admin::transactions.index', compact('transactions'));
    }
}
