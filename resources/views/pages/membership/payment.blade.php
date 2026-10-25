@extends('layouts.app')

@section('title', 'Pembayaran Membership | Ruler Of Kings')
@section('description', 'Pilih paket membership ROK dan selesaikan pembayaran untuk mendapatkan akses modul taktis, fitur bracket turnamen, atau all-access.')

@section('content')
<div class="page-container">
    <section class="page-hero">
        <div class="page-hero__inner">
            <span class="section-badge" style="letter-spacing: 2px;">OFFICIAL MEMBERSHIP CHECKOUT</span>
            <h1 class="page-title">PEMBAYARAN MEMBERSHIP ROK</h1>
            <p class="page-subtitle">
                Tingkatkan performa tim dan organizer turnamen Anda. Pilih paket langganan yang sesuai dengan kebutuhan Anda di bawah ini.
            </p>
        </div>
    </section>

    <section class="teams-section" style="padding-top: 10px; padding-bottom: 80px;">
        <div class="site-container" style="max-width: 1100px; margin: 0 auto;">
            
            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 8px; padding: 14px 20px; color: #f87171; font-size: 14px; margin-bottom: 28px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('membership.process') }}" id="paymentForm">
                @csrf
                <input type="hidden" name="membership_plan_id" id="selectedPlanId" value="{{ $selectedPlan->id }}">

                {{-- Step 1: Pilihan Paket Membership --}}
                <div style="margin-bottom: 40px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: var(--rok-gold); color: #0b0f17; font-weight: 800; font-size: 15px;">1</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: #ffffff; margin: 0;">PILIH PAKET MEMBERSHIP</h2>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                        @foreach ($plans as $plan)
                            @php $isSelected = ($plan->id === $selectedPlan->id); @endphp
                            <div class="plan-card-selectable {{ $isSelected ? 'is-selected' : '' }}" 
                                 id="plan-card-{{ $plan->id }}" 
                                 onclick="selectPlan({{ $plan->id }}, '{{ addslashes($plan->name) }}', '{{ $plan->formatted_price }}', '{{ $plan->billing_cycle }}')"
                                 style="background: #111622; border: 2px solid {{ $isSelected ? 'var(--rok-gold)' : 'var(--rok-surface-border)' }}; border-radius: var(--radius-md); padding: 28px; cursor: pointer; transition: all 0.2s ease; position: relative;">
                                
                                @if ($plan->is_featured)
                                    <span style="position: absolute; top: -12px; right: 20px; background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%); color: #0b0f17; font-size: 10px; font-weight: 900; letter-spacing: 1px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
                                        POPULAR CHOICE
                                    </span>
                                @endif

                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                    <div>
                                        <span style="font-size: 11px; font-weight: 800; color: var(--rok-gold); letter-spacing: 1.5px; text-transform: uppercase;">{{ $plan->badge_title }}</span>
                                        <h3 style="font-size: 22px; font-weight: 800; color: #ffffff; margin: 4px 0 0 0;">{{ $plan->name }}</h3>
                                    </div>
                                    <div class="plan-radio-circle" style="width: 22px; height: 22px; border-radius: 50%; border: 2px solid {{ $isSelected ? 'var(--rok-gold)' : '#475569' }}; display: flex; align-items: center; justify-content: center;">
                                        <div class="plan-radio-dot" style="width: 10px; height: 10px; border-radius: 50%; background: var(--rok-gold); display: {{ $isSelected ? 'block' : 'none' }};"></div>
                                    </div>
                                </div>

                                <div style="margin: 16px 0; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06);">
                                    <div style="font-size: 28px; font-weight: 900; color: #ffffff; font-family: var(--font-sub);">
                                        {{ $plan->formatted_price }}
                                        <span style="font-size: 13px; font-weight: 600; color: #94a3b8; font-family: var(--font-main);">/ {{ $plan->billing_cycle }}</span>
                                    </div>
                                </div>

                                <p style="font-size: 13px; color: #94a3b8; line-height: 1.5; margin-bottom: 18px;">
                                    {{ $plan->description }}
                                </p>

                                <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; color: #cbd5e1; display: grid; gap: 8px;">
                                    @if(is_array($plan->features))
                                        @foreach ($plan->features as $feat)
                                            <li style="display: flex; gap: 8px; align-items: flex-start;">
                                                <span style="color: #4ade80; font-weight: bold; flex-shrink: 0;"><i data-lucide="check" style="width:13px;height:13px;"></i></span>
                                                <span>{{ $feat }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Step 2 & 3: Rincian Pembayaran & Metode Pembayaran --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 32px; align-items: flex-start;">
                    
                    {{-- Metode Pembayaran --}}
                    <div style="background: #111622; border: 1px solid var(--rok-surface-border); border-radius: var(--radius-md); padding: 28px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: var(--rok-gold); color: #0b0f17; font-weight: 800; font-size: 15px;">2</span>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">METODE PEMBAYARAN</h2>
                        </div>

                        <div style="display: grid; gap: 14px;">
                            <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #0c0f17; border: 1px solid var(--rok-surface-border); border-radius: 8px; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="radio" name="payment_method" value="bank_bca" checked>
                                    <div>
                                        <strong style="color: #ffffff; font-size: 14px; display: block;">Transfer Bank BCA</strong>
                                        <span style="color: #94a3b8; font-size: 12px;">Verifikasi Pembayaran Virtual Account / Manual</span>
                                    </div>
                                </div>
                                <span style="font-weight: 800; color: #38bdf8; font-size: 13px;">BCA</span>
                            </label>

                            <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #0c0f17; border: 1px solid var(--rok-surface-border); border-radius: 8px; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="radio" name="payment_method" value="bank_mandiri">
                                    <div>
                                        <strong style="color: #ffffff; font-size: 14px; display: block;">Transfer Bank Mandiri</strong>
                                        <span style="color: #94a3b8; font-size: 12px;">Transfer ATM, Livin' Mandiri</span>
                                    </div>
                                </div>
                                <span style="font-weight: 800; color: #eab308; font-size: 13px;">MANDIRI</span>
                            </label>

                            <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #0c0f17; border: 1px solid var(--rok-surface-border); border-radius: 8px; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <input type="radio" name="payment_method" value="qris">
                                    <div>
                                        <strong style="color: #ffffff; font-size: 14px; display: block;">QRIS (Instant Pay)</strong>
                                        <span style="color: #94a3b8; font-size: 12px;">Gopay, OVO, Dana, ShopeePay, BCA Mobile</span>
                                    </div>
                                </div>
                                <span style="font-weight: 800; color: #ef4444; font-size: 13px;">QRIS</span>
                            </label>
                        </div>

                        <div class="form-field" style="margin-top: 20px;">
                            <label class="form-label" style="display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 6px;">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="form-textarea" rows="2" placeholder="Nama organisasi turnamen atau catatan akun..."></textarea>
                        </div>
                    </div>

                    {{-- Ringkasan Pesanan --}}
                    <div style="background: #111622; border: 1px solid var(--rok-gold); border-radius: var(--radius-md); padding: 28px; position: sticky; top: 100px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: var(--rok-gold); color: #0b0f17; font-weight: 800; font-size: 15px;">3</span>
                            <h2 style="font-size: 20px; font-weight: 800; color: #ffffff; margin: 0;">RINGKASAN TRANSAKSI</h2>
                        </div>

                        <div style="background: #0c0f17; border: 1px solid var(--rok-surface-border); border-radius: 8px; padding: 18px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                <span style="color: #94a3b8;">Paket Dipilih:</span>
                                <strong id="summaryPlanName" style="color: #ffffff;">{{ $selectedPlan->name }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                <span style="color: #94a3b8;">Siklus Tagihan:</span>
                                <span id="summaryBillingCycle" style="color: #cbd5e1;">{{ $selectedPlan->billing_cycle }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                                <span style="color: #94a3b8;">Akun Pemesan:</span>
                                <span style="color: #cbd5e1;">{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 16px;">
                                <strong style="color: #ffffff;">Total Tagihan:</strong>
                                <strong id="summaryPlanPrice" style="color: var(--rok-gold); font-size: 20px; font-family: var(--font-sub);">{{ $selectedPlan->formatted_price }}</strong>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px; font-size: 12px; color: #64748b; line-height: 1.5;">
                            &bull; Status pembayaran akan diterbitkan sebagai <strong>MENUNGGU PEMBAYARAN</strong> hingga konfirmasi transaksi diterima.<br>
                            &bull; Akses fitur turnamen atau modul pembelajaran langsung aktif setelah pembayaran terverifikasi.
                        </div>

                        <button type="submit" class="hero-button hero-button--primary" style="width: 100%; padding: 16px; font-size: 15px; font-weight: 900; border: none; cursor: pointer; text-align: center; border-radius: 6px;">
                            LANJUTKAN KE PEMBAYARAN &rarr;
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </section>
</div>

<script>
function selectPlan(planId, name, price, cycle) {
    document.getElementById('selectedPlanId').value = planId;
    document.getElementById('summaryPlanName').textContent = name;
    document.getElementById('summaryPlanPrice').textContent = price;
    document.getElementById('summaryBillingCycle').textContent = cycle;

    document.querySelectorAll('.plan-card-selectable').forEach(card => {
        card.style.borderColor = 'var(--rok-surface-border)';
        const dot = card.querySelector('.plan-radio-dot');
        const circle = card.querySelector('.plan-radio-circle');
        if (dot) dot.style.display = 'none';
        if (circle) circle.style.borderColor = '#475569';
    });

    const activeCard = document.getElementById('plan-card-' + planId);
    if (activeCard) {
        activeCard.style.borderColor = 'var(--rok-gold)';
        const activeDot = activeCard.querySelector('.plan-radio-dot');
        const activeCircle = activeCard.querySelector('.plan-radio-circle');
        if (activeDot) activeDot.style.display = 'block';
        if (activeCircle) activeCircle.style.borderColor = 'var(--rok-gold)';
    }
}
</script>
@endsection
