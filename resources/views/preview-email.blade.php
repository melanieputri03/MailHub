@extends('layout')
@section('title', 'Preview Email')

@section('content')

{{--  HEADER  --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            Preview Email
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Periksa tampilan akhir email sebelum dikirim ke penerima.</p>
    </div>

    <a href="{{ route('email.index') }}"
       class="bg-white border border-outline text-[13px] text-ink px-4 py-2.5 rounded-md flex items-center gap-2
              hover:bg-cream transition flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 12H5M11 6l-6 6 6 6"/>
        </svg>
        Kembali Edit
    </a>
</div>

@if(session('error'))
    <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-[13px] px-4 py-3 rounded-md">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 8v5M12 16h.01"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch min-h-[calc(100vh-140px)]">

    {{--  KIRI: PREVIEW EMAIL (2/3)  --}}
    <div class="lg:col-span-2">
        <div class="bg-white border border-outline rounded-lg overflow-hidden shadow-sm h-full flex flex-col">

            @include('partials.email-header')

            <div class="px-8 py-8 flex-1">
                <div class="text-center mb-6">
                    <h2 class="text-[20px] font-bold text-maroon tracking-wide">{{ $data['nama'] }}</h2>
                </div>
                <div class="text-[13px] text-ink leading-relaxed whitespace-pre-line">{{ $data['body'] }}</div>
            </div>

            @include('partials.email-footer')
        </div>
    </div>

    {{--  KANAN: INFO PENERIMA (1/3)  --}}
    <div class="lg:col-span-1 flex flex-col h-full">
        <div class="space-y-4 flex-1">

            {{-- Subject --}}
            <div class="bg-white rounded-lg border border-outline p-4">
                <div class="text-[10px] text-muted uppercase tracking-wider mb-1">Subject</div>
                <div class="text-[13px] font-semibold text-ink break-words">{{ $data['subject'] }}</div>
            </div>

            {{-- Nama Internal --}}
            <div class="bg-white rounded-lg border border-outline p-4">
                <div class="text-[10px] text-muted uppercase tracking-wider mb-1">Nama Internal</div>
                <div class="text-[13px] font-semibold text-ink">{{ $data['nama'] }}</div>
            </div>

            {{-- Ringkasan Penerima --}}
            <div class="bg-white rounded-lg border border-outline p-4">
                <div class="text-[10px] text-muted uppercase tracking-wider mb-2">Penerima</div>
                <div class="flex items-baseline gap-2 mb-3">
                    <div class="text-[28px] font-bold text-ink leading-none">{{ $penerima->count() }}</div>
                    <div class="text-[12px] text-muted">orang</div>
                </div>
                <div class="space-y-1.5 text-[11px]">
                    <div class="flex justify-between gap-2">
                        <span class="text-muted flex-shrink-0">Mode</span>
                        <span class="text-ink font-semibold text-right">
                            @if($data['penerima_mode'] === 'semua') Semua Penerima Aktif
                            @elseif($data['penerima_mode'] === 'divisi') Per Divisi
                            @elseif($data['penerima_mode'] === 'grup') Per Grup
                            @else Penerima Tertentu
                            @endif
                        </span>
                    </div>

                    @if($data['penerima_mode'] === 'divisi' && count($divisiDipilih) > 0)
                        <div class="flex justify-between gap-2">
                            <span class="text-muted flex-shrink-0">Divisi</span>
                            <span class="text-ink font-semibold text-right">{{ implode(', ', $divisiDipilih) }}</span>
                        </div>
                    @endif

                    @if($data['penerima_mode'] === 'grup' && count($grupDipilih) > 0)
                        <div class="flex justify-between gap-2">
                            <span class="text-muted flex-shrink-0">Grup</span>
                            <span class="text-ink font-semibold text-right">{{ implode(', ', $grupDipilih) }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Detail Penerima --}}
            @if($penerima->count() > 0)
                <details class="bg-white rounded-lg border border-outline overflow-hidden">
                    <summary class="px-4 py-3 cursor-pointer text-[12px] font-semibold text-ink hover:bg-cream/50 transition flex items-center justify-between">
                        <span>Lihat daftar penerima ({{ $penerima->count() }})</span>
                        <svg class="w-3.5 h-3.5 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </summary>
                    <div class="border-t border-outline max-h-64 overflow-y-auto p-3 space-y-1.5 text-[11px]">
                        @foreach($penerima->take(50) as $p)
                            <div class="flex items-center gap-2 py-1">
                                <div class="w-6 h-6 rounded-full bg-maroon/10 flex items-center justify-center text-[9px] font-bold text-maroon flex-shrink-0 uppercase">
                                    {{ substr($p->nama, 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-ink truncate">{{ $p->nama }}</div>
                                    <div class="text-[10px] text-muted truncate">{{ $p->email }}</div>
                                </div>
                                <span class="text-[9px] text-muted bg-cream px-1.5 py-0.5 rounded flex-shrink-0">
                                    {{ $p->divisi->nama ?? '-' }}
                                </span>
                            </div>
                        @endforeach

                        @if($penerima->count() > 50)
                            <div class="text-center text-[10px] text-muted pt-2 border-t border-outline/60 mt-2">
                                ... dan {{ $penerima->count() - 50 }} penerima lainnya
                            </div>
                        @endif
                    </div>
                </details>
            @else
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                        </svg>
                        <div class="text-[12px] text-red-700 leading-relaxed">
                            <strong>Tidak ada penerima terpilih.</strong><br>
                            Kembali edit dan pilih minimal 1 penerima.
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tombol Aksi --}}
            <form id="formKirim" method="POST" action="{{ route('email.kirim') }}">
                @csrf
                <input type="hidden" name="nama"          value="{{ $data['nama'] }}">
                <input type="hidden" name="subject"       value="{{ $data['subject'] }}">
                <input type="hidden" name="body"          value="{{ $data['body'] }}">
                <input type="hidden" name="template_id"   value="{{ $data['template_id'] ?? '' }}">
                <input type="hidden" name="penerima_mode" value="{{ $data['penerima_mode'] }}">

                @if(!empty($data['divisi_ids']))
                    @foreach($data['divisi_ids'] as $id)
                        <input type="hidden" name="divisi_ids[]" value="{{ $id }}">
                    @endforeach
                @endif
                @if(!empty($data['grup_ids']))
                    @foreach($data['grup_ids'] as $id)
                        <input type="hidden" name="grup_ids[]" value="{{ $id }}">
                    @endforeach
                @endif
                @if(!empty($data['penerima_ids']))
                    @foreach($data['penerima_ids'] as $id)
                        <input type="hidden" name="penerima_ids[]" value="{{ $id }}">
                    @endforeach
                @endif

                <button type="submit"
                        @if($penerima->count() === 0) disabled @endif
                        onclick="return konfirmasiKirim(event, {{ $penerima->count() }})"
                        class="w-full bg-gold hover:bg-goldD disabled:bg-muted disabled:cursor-not-allowed
                            text-white text-[13px] font-semibold px-5 py-3 rounded-md
                            flex items-center justify-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                    </svg>
                    Kirim Email ke {{ $penerima->count() }} Penerima
                </button>
            </form>

            <a href="{{ route('email.index') }}"
               class="block w-full text-center bg-white border border-outline text-[13px] text-ink
                      px-5 py-2.5 rounded-md hover:bg-cream transition">
                Kembali Edit
            </a>

            {{-- Info SMTP --}}
            <div class="bg-gold/5 border border-gold/20 rounded-lg p-3 flex items-start gap-2">
                <svg class="w-4 h-4 text-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                </svg>
                <p class="text-[11px] text-ink leading-relaxed">
                    Email akan dikirim melalui <strong>SMTP</strong> satu per satu.
                    Hasil pengiriman dicatat otomatis di halaman Riwayat.
                </p>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function konfirmasiKirim(e, jumlah) {
        if (jumlah === 0) {
            e.preventDefault();
            return false;
        }
        return confirm('Kirim email ini ke ' + jumlah + ' penerima?\n\nProses tidak dapat dibatalkan.');
    }
</script>
@endpush