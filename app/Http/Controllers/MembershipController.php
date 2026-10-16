<?php

namespace App\Http\Controllers;

use App\Models\MembershipOrder;
use App\Models\MembershipPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(Request $request): View
    {
        $plans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();
        $selectedSlug = $request->query('plan', 'challenger');
        $selectedPlan = $plans->firstWhere('slug', $selectedSlug) ?? $plans->first();

        return view('pages.membership.index', compact('plans', 'selectedPlan'));
    }

    public function payment(Request $request): View
    {
        $plans = MembershipPlan::where('is_active', true)->orderBy('sort_order')->get();
        $selectedSlug = $request->query('plan', 'challenger');
        $selectedPlan = $plans->firstWhere('slug', $selectedSlug) ?? $plans->first();

        return view('pages.membership.payment', compact('plans', 'selectedPlan'));
    }

    public function processPayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'payment_method' => ['required', 'in:bank_bca,bank_mandiri,qris,ewallet_gopay'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $plan = MembershipPlan::findOrFail($validated['membership_plan_id']);

        $orderNumber = 'ROK-ORD-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999);
        $reference = 'TRX-' . strtoupper(Str::random(10));

        $order = MembershipOrder::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'membership_plan_id' => $plan->id,
            'amount' => $plan->price,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'payment_reference' => $reference,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('membership.invoice', ['order' => $order->order_number])
            ->with('success', 'Pesanan membership berhasil dibuat! Silakan selesaikan pembayaran sesuai instruksi di bawah.');
    }

    public function invoice(string $orderNumber): View
    {
        $order = MembershipOrder::with(['plan', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses invoice tidak diizinkan.');
        }

        return view('pages.membership.invoice', compact('order'));
    }
}
