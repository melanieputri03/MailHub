@extends('layout')
@section('title', 'Riwayat Pengiriman')

@section('content')

{{--  HEADER --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                <path d="M3 3v5h5M12 7v5l3 2"/>
            </svg>
            Riwayat Pengiriman
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Catatan lengkap email yang pernah dikirim.</p>
    </div>

    <a href="{{ route('riwayat') }}"
       class="bg-white border border-outline text-[13px] text-ink px-4 py-2.5 rounded-md flex items-center gap-2
              hover:bg-cream transition flex-shrink-0">
        <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
            <path d="M3 3v5h5"/>
        </svg>
        Refresh
    </a>
</div>

{{--  FILTER  --}}
<div class="bg-white rounded-lg border border-outline p-4 mb-4">
    <form method="GET" action="{{ route('riwayat') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
        {{-- Search --}}
        <div class="relative md:col-span-2">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari subject email..."
                   class="w-full border border-outline rounded-md pl-9 pr-3 py-2 text-[12px] text-ink
                          focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
        </div>

        {{-- Filter status --}}
        <select name="status"
                class="border border-outline rounded-md px-3 py-2 text-[12px] text-ink bg-white
                       focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
            <option value="">Semua Status</option>
            <option value="sent"   {{ request('status') === 'sent' ? 'selected' : '' }}>Berhasil</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
        </select>

        {{-- Tombol --}}
        <div class="md:col-span-3 flex justify-end gap-2">
            <a href="{{ route('riwayat') }}"
               class="text-[12px] border border-outline rounded-md px-3 py-2 text-muted hover:bg-cream transition">
                Reset
            </a>
            <button type="submit"
                    class="text-[12px] bg-maroon text-white rounded-md px-4 py-2 font-semibold hover:bg-maroonD transition">
                Terapkan Filter
            </button>
        </div>
    </form>
</div>

{{--  TABEL  --}}
<div class="bg-white rounded-lg border border-outline overflow-hidden">

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
                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline/60">

                @forelse($riwayat as $r)
                    <tr class="hover:bg-cream/40 transition">
                        {{-- Waktu --}}
                        <td class="px-5 py-4 text-[12px] text-muted font-mono whitespace-nowrap">
                            {{ $r->sent_at ? $r->sent_at->format('d M Y H:i') : ($r->created_at ? $r->created_at->format('d M Y H:i') : '-') }}
                        </td>

                        {{-- Subject --}}
                        <td class="px-5 py-4 text-ink font-medium">
                            {{ $r->subject }}
                        </td>

                        {{-- Nama Internal --}}
                        <td class="px-5 py-4 text-muted text-[12px]">
                            {{ $r->nama }}
                        </td>

                        {{-- Jumlah Penerima --}}
                        <td class="px-5 py-4 text-center text-ink font-semibold">
                            {{ $r->total_penerima }}
                        </td>

                        {{-- Berhasil --}}
                        <td class="px-5 py-4 text-center">
                            <span class="text-green-600 font-bold">{{ $r->total_berhasil }}</span>
                        </td>

                        {{-- Gagal --}}
                        <td class="px-5 py-4 text-center">
                            @if($r->total_gagal > 0)
                                <span class="text-red-600 font-bold">{{ $r->total_gagal }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 text-center">
                            @if($r->status === 'sent' && $r->total_gagal === 0)
                                <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Berhasil
                                </span>
                            @elseif($r->status === 'sent' && $r->total_gagal > 0)
                                <span class="bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Sebagian
                                </span>
                            @else
                                <span class="bg-red-50 text-red-700 border border-red-200 text-[10px] px-2 py-1 rounded font-bold uppercase">
                                    Gagal
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 text-right">
                            <button type="button" onclick="toggleDetail({{ $r->email_id }})"
                                    class="text-[11px] border border-outline rounded px-3 py-1 text-ink
                                           hover:bg-cream transition font-medium">
                                Detail
                            </button>
                        </td>
                    </tr>

                    {{-- DETAIL ROW (expand) --}}
                    <tr id="detail-{{ $r->email_id }}" class="hidden bg-cream/30">
                        <td colspan="8" class="px-5 py-5">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                                {{-- Info Email --}}
                                <div class="bg-white rounded-md border border-outline p-4">
                                    <div class="text-[11px] font-bold text-ink mb-3 uppercase tracking-wider">Informasi Email</div>
                                    <div class="space-y-2 text-[12px]">
                                        <div class="flex justify-between">
                                            <span class="text-muted">Nama Internal</span>
                                            <span class="text-ink font-semibold">{{ $r->nama }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted">Subject</span>
                                            <span class="text-ink font-semibold text-right">{{ $r->subject }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted">Template</span>
                                            <span class="text-ink font-semibold">
                                                @if($r->template)
                                                    {{ $r->template->nama }}
                                                @else
                                                    <span class="text-muted italic">Buat dari Awal</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-muted">Waktu Kirim</span>
                                            <span class="text-ink font-semibold">
                                                {{ $r->sent_at ? $r->sent_at->format('d M Y H:i') : '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ringkasan --}}
                                <div class="bg-white rounded-md border border-outline p-4">
                                    <div class="text-[11px] font-bold text-ink mb-3 uppercase tracking-wider">Ringkasan Pengiriman</div>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="text-center">
                                            <div class="text-[20px] font-bold text-ink">{{ $r->total_penerima }}</div>
                                            <div class="text-[10px] text-muted uppercase">Total</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-[20px] font-bold text-green-600">{{ $r->total_berhasil }}</div>
                                            <div class="text-[10px] text-muted uppercase">Berhasil</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-[20px] font-bold text-red-600">{{ $r->total_gagal }}</div>
                                            <div class="text-[10px] text-muted uppercase">Gagal</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Lihat Isi Email --}}
                            <div class="bg-white rounded-md border border-outline p-4 mb-4">
                                <button type="button" onclick="toggleBodyEmail({{ $r->email_id }})"
                                        class="text-[12px] text-gold font-semibold hover:underline flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <span id="btn-body-text-{{ $r->email_id }}">Lihat Isi Email</span>
                                </button>

                                {{-- Body Email (hidden by default) --}}
                                <div id="body-email-{{ $r->email_id }}" class="hidden mt-4 pt-4 border-t border-outline">
                                    <div class="text-[10px] text-muted uppercase tracking-wider mb-2">Isi Email:</div>
                                    <div class="text-[13px] text-ink leading-relaxed whitespace-pre-line bg-cream/30 rounded-md p-4">
                                        {{ $r->body }}
                                    </div>
                                </div>
                            </div>

                            {{-- Daftar Penerima --}}
                            <div class="bg-white rounded-md border border-outline overflow-hidden">
                                <div class="px-4 py-2.5 border-b border-outline bg-cream/50">
                                    <div class="text-[11px] font-bold text-ink uppercase tracking-wider">
                                        Daftar Penerima ({{ $r->emailLog->count() }})
                                    </div>
                                </div>
                                <div class="max-h-64 overflow-y-auto divide-y divide-outline/60">
                                    @foreach($r->emailLog as $log)
                                        <div class="flex items-center gap-3 px-4 py-2.5 text-[11px]">
                                            @if($log->status === 'success')
                                                <span class="w-5 h-5 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">✓</span>
                                            @else
                                                <span class="w-5 h-5 rounded-full bg-red-100 text-red-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">✕</span>
                                            @endif

                                            <div class="flex-1 min-w-0">
                                                <div class="text-ink font-medium truncate">
                                                    {{ $log->penerima->nama ?? '-' }}
                                                </div>
                                                <div class="text-muted text-[10px] font-mono truncate">
                                                    {{ $log->penerima_email }}
                                                </div>
                                            </div>

                                            @if($log->error_message)
                                                <span class="text-red-600 text-[10px] italic flex-shrink-0 max-w-[200px] truncate" title="{{ $log->error_message }}">
                                                    {{ $log->error_message }}
                                                </span>
                                            @endif

                                            <span class="text-muted text-[10px] font-mono flex-shrink-0">
                                                {{ $log->sent_at ? $log->sent_at->format('d M H:i') : '-' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-muted text-[12px]">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                                    <path d="M3 3v5h5M12 7v5l3 2"/>
                                </svg>
                                <div>Belum ada riwayat pengiriman.</div>
                                <div class="text-[11px]">Kirim email dulu dari halaman "Buat Email".</div>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
<div class="px-5 py-4 border-t border-outline flex items-center justify-between flex-wrap gap-3">
    <span class="text-[11px] text-muted">
        Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }}
        dari {{ $riwayat->total() }} riwayat
    </span>

    @if($riwayat->hasPages())
        <div class="flex items-center gap-1.5">

            {{-- Tombol Prev --}}
            @if($riwayat->onFirstPage())
                <span class="text-[12px] px-3 py-1.5 border border-outline rounded text-muted/40 cursor-not-allowed">
                    Prev
                </span>
            @else
                <a href="{{ $riwayat->previousPageUrl() }}"
                   class="text-[12px] px-3 py-1.5 border border-outline rounded text-ink hover:bg-cream transition">
                    Prev
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach($riwayat->links()->elements[0] as $page => $url)
                @if($page == $riwayat->currentPage())
                    <span class="text-[12px] px-3 py-1.5 border border-outline rounded bg-cream text-ink font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="text-[12px] px-3 py-1.5 border border-outline rounded text-muted hover:bg-cream transition">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if($riwayat->hasMorePages())
                <a href="{{ $riwayat->nextPageUrl() }}"
                   class="text-[12px] px-3 py-1.5 border border-outline rounded text-ink hover:bg-cream transition">
                    Next
                </a>
            @else
                <span class="text-[12px] px-3 py-1.5 border border-outline rounded text-muted/40 cursor-not-allowed">
                    Next
                </span>
            @endif

        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function toggleDetail(id) {
        const currentRow = document.getElementById('detail-' + id);
        const isCurrentlyOpen = !currentRow.classList.contains('hidden');

        document.querySelectorAll('[id^="detail-"]').forEach(row => {
            row.classList.add('hidden');
        });

        document.querySelectorAll('[id^="body-email-"]').forEach(body => {
            body.classList.add('hidden');
        });
        document.querySelectorAll('[id^="btn-body-text-"]').forEach(btn => {
            btn.textContent = 'Lihat Isi Email';
        });

        if (!isCurrentlyOpen) {
            currentRow.classList.remove('hidden');
        }
    }

    function toggleBodyEmail(id) {
        const body = document.getElementById('body-email-' + id);
        const btn  = document.getElementById('btn-body-text-' + id);

        if (body && btn) {
            body.classList.toggle('hidden');
            btn.textContent = body.classList.contains('hidden')
                ? 'Lihat Isi Email'
                : 'Sembunyikan Isi Email';
        }
    }
</script>
@endpush