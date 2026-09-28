{{-- ============ HEADER EMAIL BIC ============ --}}
<div class="bg-white px-6 py-5 border-b-2 border-maroon">
    <div class="flex items-center gap-4">

        {{-- LOGO LANGSUNG (TANPA BACKGROUND) --}}
        <img src="{{ asset('images/logo-batamindo.png') }}"
             alt="Batamindo"
             class="h-14 w-auto object-contain flex-shrink-0">

        {{-- Nama Perusahaan --}}
        <div class="flex-1 min-w-0">
            <div class="text-maroon font-bold text-[16px] tracking-tight leading-tight">
                BATAMINDO INDUSTRIAL PARK
            </div>
            <div class="text-muted text-[11px] tracking-[0.1em] mt-0.5">
                PT BATAMINDO INVESTMENT CAKRAWALA
            </div>
        </div>

        {{-- Tanggal & Ref --}}
        <div class="text-right text-[10px] text-muted leading-snug flex-shrink-0">
            <div class="font-semibold text-ink">{{ date('d M Y') }}</div>
            <div>Ref: BIC/{{ date('Y') }}</div>
        </div>

    </div>
</div>