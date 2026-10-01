@extends('layout')
@section('title', 'Dashboard')

@section('content')

{{-- HEADER --}}
<div class="mb-5">
    <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
        <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <rect x="3.5" y="3.5" width="7" height="7" rx="1"/>
            <rect x="13.5" y="3.5" width="7" height="7" rx="1"/>
            <rect x="3.5" y="13.5" width="7" height="7" rx="1"/>
            <rect x="13.5" y="13.5" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
    </h1>
    <p class="text-[13px] text-muted mt-1 ml-7">Ringkasan aktivitas pengiriman email internal.</p>
</div>

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    {{-- Total Penerima --}}
    <div class="bg-white rounded-lg border border-outline p-5">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-[11px] text-muted font-semibold uppercase tracking-wider">Total Penerima</div>
                <div class="text-[28px] font-bold text-ink mt-1.5 leading-none">
                    {{ number_format($totalPenerima, 0, ',', '.') }}
                </div>
            </div>
            <div class="w-9 h-9 bg-maroon/10 rounded-md flex items-center justify-center flex-shrink-0">
                <svg class="text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <circle cx="9" cy="11" r="2"/>
                    <path d="M5.5 16.5c.8-1.6 2.4-2.5 3.5-2.5s2.7.9 3.5 2.5"/>
                    <path d="M15 10h4M15 13h3"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-0.5 rounded font-semibold">
                +{{ $penerimaBulanIni }} baru
            </span>
            <span class="text-[10px] text-muted self-center">bulan ini</span>
        </div>
    </div>

    {{-- Total Grup --}}
    <div class="bg-white rounded-lg border border-outline p-5">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-[11px] text-muted font-semibold uppercase tracking-wider">Total Grup</div>
                <div class="text-[28px] font-bold text-ink mt-1.5 leading-none">
                    {{ $totalGrup }} <span class="text-[13px] font-medium text-muted">Grup</span>
                </div>
            </div>
            <div class="w-9 h-9 bg-gold/10 rounded-md flex items-center justify-center flex-shrink-0">
                <svg class="text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    <circle cx="9" cy="8" r="3"/>
                    <circle cx="17" cy="9" r="2.3"/>
                    <path d="M3.5 19a5.5 5.5 0 0 1 11 0"/>
                    <path d="M14.5 19a4.5 4.5 0 0 1 6-4"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <span class="bg-cream text-muted border border-outline text-[10px] px-2 py-0.5 rounded font-semibold">
                {{ $totalDivisi }} Divisi
            </span>
            <span class="text-[10px] text-muted self-center">terdaftar</span>
        </div>
    </div>

    {{-- Total Email --}}
    <div class="bg-white rounded-lg border border-outline p-5">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-[11px] text-muted font-semibold uppercase tracking-wider">Total Email Dibuat</div>
                <div class="text-[28px] font-bold text-ink mt-1.5 leading-none">
                    {{ $totalEmail }}
                </div>
            </div>
            <div class="w-9 h-9 bg-gold/10 rounded-md flex items-center justify-center flex-shrink-0">
                <svg class="text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    <path d="M3 7.5 12 13l9-5.5"/>
                    <path d="M21 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    <circle cx="16.5" cy="17" r="1.5" fill="currentColor" stroke="none"/>
                </svg>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <span class="bg-gold/15 text-gold border border-gold/30 text-[10px] px-2 py-0.5 rounded font-semibold">
                {{ $emailBulanIni }} bulan ini
            </span>
        </div>
    </div>
</div>

{{-- STATISTIK BERHASIL / GAGAL --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

    {{-- Berhasil --}}
    <div class="bg-white rounded-lg border border-outline p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-[11px] text-muted font-semibold uppercase tracking-wider">Email Berhasil Dikirim</div>
                <div class="text-[22px] font-bold text-green-600 mt-0.5 leading-none">
                    {{ number_format($totalBerhasil, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Gagal --}}
    <div class="bg-white rounded-lg border border-outline p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-[11px] text-muted font-semibold uppercase tracking-wider">Email Gagal Dikirim</div>
                <div class="text-[22px] font-bold text-red-600 mt-0.5 leading-none">
                    {{ number_format($totalGagal, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- AKTIVITAS TERBARU --}}
<div class="bg-white rounded-lg border border-outline">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-outline flex flex-col md:flex-row md:items-center justify-between gap-3">
        <h2 class="font-semibold text-[14px] text-ink flex items-center gap-2">
            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                <path d="M3 3v5h5M12 7v5l3 2"/>
            </svg>
            Aktivitas Pengiriman Terbaru
            <span class="text-muted font-normal text-[12px]">(5 email terakhir)</span>
        </h2>

        <a href="{{ route('riwayat') }}"
           class="text-[12px] text-gold font-semibold hover:underline flex items-center gap-1 flex-shrink-0">
            Lihat semua riwayat
            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        </a>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead class="bg-cream/70 text-muted text-[11px] uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold">Waktu Kirim</th>
                    <th class="px-5 py-3 text-left font-semibold">Subject</th>
                    <th class="px-5 py-3 text-left font-semibold">Nama Internal</th>
                    <th class="px-5 py-3 text-center font-semibold">Penerima</th>
                    <th class="px-5 py-3 text-center font-semibold">Berhasil</th>
                    <th class="px-5 py-3 text-center font-semibold">Gagal</th>
                    <th class="px-5 py-3 text-center font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline/60">

                @forelse($aktivitasTerbaru as $a)
                    <tr class="hover:bg-cream/40 transition">

                        <td class="px-5 py-4 text-[12px] text-muted font-mono whitespace-nowrap">
                            {{ $a->sent_at ? $a->sent_at->format('d M Y H:i') : '-' }}
                        </td>

                        <td class="px-5 py-4 text-ink font-medium">
                            {{ $a->subject }}
                        </td>

                        <td class="px-5 py-4 text-muted text-[12px]">
                            {{ $a->nama }}
                        </td>

                        <td class="px-5 py-4 text-center text-ink font-semibold">
                            {{ $a->total_penerima }}
                        </td>

                        <td class="px-5 py-4 text-center">
                            <span class="text-green-600 font-bold">{{ $a->total_berhasil }}</span>
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($a->total_gagal > 0)
                                <span class="text-red-600 font-bold">{{ $a->total_gagal }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($a->status === 'sent' && $a->total_gagal === 0)
                                <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Berhasil
                                </span>
                            @elseif($a->status === 'sent' && $a->total_gagal > 0)
                                <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Sebagian
                                </span>
                            @else
                                <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Gagal
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-muted text-[12px]">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                                    <path d="M3 3v5h5M12 7v5l3 2"/>
                                </svg>
                                <div>Belum ada email yang dikirim.</div>
                                <div class="text-[11px]">Mulai kirim email dari halaman "Buat Email".</div>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection