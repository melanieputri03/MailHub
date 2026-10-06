@extends('layout')
@section('title', 'Kelola Penerima')

@section('content')

{{--  HEADER  --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2"/>
                <circle cx="9" cy="11" r="2"/>
                <path d="M5.5 16.5c.8-1.6 2.4-2.5 3.5-2.5s2.7.9 3.5 2.5"/>
                <path d="M15 10h4M15 13h3"/>
            </svg>
            Kelola Penerima
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Daftar kontak karyawan internal Batamindo Investment Cakrawala.</p>
    </div>

    <button onclick="bukaModalPenerima(null)"
            class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-4 py-2.5 rounded-md flex items-center gap-2
                   transition shadow-sm flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="9" cy="8" r="3"/>
            <path d="M3.5 19a5.5 5.5 0 0 1 11 0M18 8v6M15 11h6"/>
        </svg>
        Tambah Penerima
    </button>
</div>

{{--  CARD UTAMA  --}}
<div class="bg-white rounded-lg border border-outline">

    {{--  FILTER BAR  --}}
    <form method="GET" action="{{ route('penerima.index') }}" class="px-5 py-3.5 border-b border-outline grid grid-cols-1 md:grid-cols-4 gap-3">
        {{-- Search --}}
        <div class="relative md:col-span-2">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                </svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="w-full border border-outline rounded-md pl-9 pr-3 py-2 text-[12px] text-ink
                          focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
        </div>

        {{-- Filter Divisi --}}
        <select name="divisi_id"
                class="border border-outline rounded-md px-3 py-2 text-[12px] text-ink bg-white
                       focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
            <option value="">Semua Divisi</option>
            @foreach($divisi as $d)
                <option value="{{ $d->divisi_id }}" {{ request('divisi_id') == $d->divisi_id ? 'selected' : '' }}>
                    {{ $d->nama }}
                </option>
            @endforeach
        </select>

        {{-- Filter Status --}}
        <select name="status"
                class="border border-outline rounded-md px-3 py-2 text-[12px] text-ink bg-white
                       focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
        </select>

        {{-- Submit --}}
        <div class="md:col-span-4 flex justify-end gap-2">
            <a href="{{ route('penerima.index') }}"
               class="text-[12px] border border-outline rounded-md px-3 py-2 text-muted hover:bg-cream transition">
                Reset Filter
            </a>
            <button type="submit"
                    class="text-[12px] bg-maroon text-white rounded-md px-4 py-2 font-semibold hover:bg-maroonD transition">
                Terapkan Filter
            </button>
        </div>
    </form>

    {{--  TABEL PENERIMA  --}}
    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead class="bg-cream/70 text-muted text-[11px] uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold">Kontak &amp; Inisial</th>
                    <th class="px-5 py-3 text-left font-semibold">Divisi / Jabatan</th>
                    <th class="px-5 py-3 text-left font-semibold">Grup Anggota</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline/60">

                @forelse($penerima as $p)
                    <tr class="hover:bg-cream/40 transition">

                        {{-- Kontak & Inisial --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-maroon/10 text-maroon flex items-center justify-center font-bold text-[11px] flex-shrink-0 uppercase">
                                    {{ substr($p->nama, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-ink truncate">{{ $p->nama }}</div>
                                    <div class="text-[11px] text-muted truncate">{{ $p->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Divisi / Jabatan --}}
                        <td class="px-5 py-4">
                            <div class="font-semibold text-ink">{{ $p->divisi->nama ?? '-' }}</div>
                            <div class="text-[11px] text-muted">{{ $p->jabatan ?? '-' }}</div>
                        </td>

                        {{-- Grup Anggota --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($p->grup as $g)
                                    <span class="bg-cream text-muted text-[10px] px-2 py-0.5 rounded font-medium">
                                        {{ $g->nama }}
                                    </span>
                                @empty
                                    <span class="text-[11px] text-muted italic">Belum ada grup</span>
                                @endforelse
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4">
                            @if($p->status === 'active')
                                <span class="bg-green-50 text-green-700 border border-green-200 text-[11px] px-2 py-0.5 rounded font-semibold">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-cream text-muted border border-outline text-[11px] px-2 py-0.5 rounded font-semibold">
                                    Non-Aktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex gap-1">
                                {{-- Tombol Edit --}}
                                <button onclick='bukaModalPenerima(@json($p))'
                                        class="w-7 h-7 hover:bg-cream rounded flex items-center justify-center text-muted hover:text-gold transition"
                                        title="Edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                    </svg>
                                </button>

                                {{-- Tombol Hapus (panggil modal) --}}
                                <button type="button"
                                        onclick="bukaModalHapus({{ $p->penerima_id }}, '{{ addslashes($p->nama) }}')"
                                        class="w-7 h-7 hover:bg-red-50 rounded flex items-center justify-center text-muted hover:text-red-600 transition"
                                        title="Hapus">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-muted text-[12px]">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <circle cx="9" cy="11" r="2"/>
                                    <path d="M5.5 16.5c.8-1.6 2.4-2.5 3.5-2.5s2.7.9 3.5 2.5"/>
                                </svg>
                                <div>Belum ada data penerima.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{--  PAGINATION  --}}
    <div class="px-5 py-3.5 border-t border-outline flex items-center justify-between flex-wrap gap-3">
        <span class="text-[11px] text-muted">
            Menampilkan {{ $penerima->firstItem() ?? 0 }} - {{ $penerima->lastItem() ?? 0 }}
            dari {{ $penerima->total() }} penerima
        </span>
        <div>
            {{ $penerima->links() }}
        </div>
    </div>

</div>


{{--  MODAL TAMBAH/EDIT PENERIMA  --}}
<div id="modalPenerima" class="hidden fixed inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-lg border border-outline">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-outline flex justify-between items-start">
            <div class="flex gap-3">
                <div class="w-10 h-10 bg-gold/10 rounded-md flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="8" r="3"/>
                        <path d="M3.5 19a5.5 5.5 0 0 1 11 0M18 8v6M15 11h6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-[15px] text-ink" id="modalTitle">Tambah Penerima</h3>
                    <p class="text-[12px] text-muted mt-0.5">Isi data penerima email.</p>
                </div>
            </div>
            <button type="button" onclick="tutupModalPenerima()"
                    class="text-muted hover:text-ink w-7 h-7 flex items-center justify-center rounded hover:bg-cream transition">
                ✕
            </button>
        </div>

        {{-- FORM --}}
        <form id="formPenerima" method="POST" action="{{ route('penerima.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="penerima_id" id="formPenerimaId" value="">

            <div class="px-6 py-5 space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="formNama" required
                           placeholder="Misal: Bambang Sugianto"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="formEmail" required
                           placeholder="nama@batamindo.co.id"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                {{-- Divisi & Jabatan --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[12px] font-semibold text-ink">
                            Divisi <span class="text-red-500">*</span>
                        </label>
                        <select name="divisi_id" id="formDivisi" required
                                class="w-full mt-1.5 border border-outline rounded-md px-3 py-2.5 text-[13px] text-ink bg-white
                                       focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisi as $d)
                                <option value="{{ $d->divisi_id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[12px] font-semibold text-ink">Jabatan</label>
                        <input type="text" name="jabatan" id="formJabatan"
                               placeholder="Opsional"
                               class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                      focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">Status Keaktifan</label>
                    <div class="mt-2 flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="active" id="statusAktif" checked class="accent-gold">
                            <span class="bg-green-50 text-green-700 border border-green-200 text-[11px] px-2 py-0.5 rounded font-semibold">
                                Aktif
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="inactive" id="statusNonaktif" class="accent-gold">
                            <span class="bg-cream text-muted border border-outline text-[11px] px-2 py-0.5 rounded font-semibold">
                                Non-Aktif
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-4 border-t border-outline flex justify-end gap-3">
                <button type="button" onclick="tutupModalPenerima()"
                        class="bg-white border border-outline text-[13px] text-ink px-5 py-2.5 rounded-md hover:bg-cream transition">
                    Batal
                </button>
                <button type="submit"
                        class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-5 py-2.5 rounded-md
                               flex items-center gap-2 transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>


{{--  MODAL KONFIRMASI HAPUS  --}}
<div id="modalHapus" class="hidden fixed inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md border border-outline">

        {{-- BODY --}}
        <div class="px-6 py-6 flex flex-col items-center text-center">

            {{-- Icon Warning --}}
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M12 3 2 21h20L12 3z"/>
                    <path d="M12 9v5M12 17h.01"/>
                </svg>
            </div>

            {{-- Judul --}}
            <h3 class="font-bold text-[17px] text-ink mb-2">Hapus Penerima?</h3>

            {{-- Deskripsi --}}
            <p class="text-[13px] text-muted leading-relaxed">
                Anda akan menghapus penerima:
            </p>
            <p class="text-[14px] font-semibold text-ink mt-2 mb-2 px-4 py-2 bg-cream/60 rounded-md" id="hapusNamaPenerima">
                -
            </p>
            <p class="text-[12px] text-muted leading-relaxed">
                Data akan dihapus dari daftar. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        {{-- FORM HAPUS (hidden) --}}
        <form id="formHapusPenerima" method="POST" action="">
            @csrf
            @method('DELETE')
        </form>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t border-outline grid grid-cols-2 gap-3">
            <button type="button" onclick="tutupModalHapus()"
                    class="bg-white border border-outline text-[13px] text-ink px-5 py-3 rounded-md hover:bg-cream transition font-medium">
                Batal
            </button>
            <button type="submit" form="formHapusPenerima"
                    class="bg-red-600 hover:bg-red-700 text-white text-[13px] font-semibold px-5 py-3 rounded-md
                           flex items-center justify-center gap-2 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const baseUrl = "{{ url('/penerima') }}";

    //  MODAL TAMBAH/EDIT 
    function bukaModalPenerima(data) {
        const modal = document.getElementById('modalPenerima');
        const form  = document.getElementById('formPenerima');
        const title = document.getElementById('modalTitle');

        if (data) {
            // EDIT MODE
            title.textContent = 'Edit Penerima';
            form.action = baseUrl + '/' + data.penerima_id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formPenerimaId').value = data.penerima_id;

            document.getElementById('formNama').value     = data.nama;
            document.getElementById('formEmail').value    = data.email;
            document.getElementById('formDivisi').value   = data.divisi_id;
            document.getElementById('formJabatan').value  = data.jabatan || '';
            document.getElementById('statusAktif').checked     = data.status === 'active';
            document.getElementById('statusNonaktif').checked  = data.status === 'inactive';
        } else {
            // TAMBAH MODE
            title.textContent = 'Tambah Penerima';
            form.action = baseUrl;
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formPenerimaId').value = '';
            form.reset();
            document.getElementById('statusAktif').checked = true;
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalPenerima() {
        document.getElementById('modalPenerima').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('modalPenerima')?.addEventListener('click', function (e) {
        if (e.target === this) tutupModalPenerima();
    });

    //  MODAL HAPUS 
    function bukaModalHapus(id, nama) {
        const modal = document.getElementById('modalHapus');
        const form  = document.getElementById('formHapusPenerima');

        form.action = baseUrl + '/' + id;
        document.getElementById('hapusNamaPenerima').textContent = nama;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalHapus() {
        document.getElementById('modalHapus').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('modalHapus')?.addEventListener('click', function (e) {
        if (e.target === this) tutupModalHapus();
    });

    //  ESC HANDLER 
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            tutupModalPenerima();
            tutupModalHapus();
        }
    });

    //  VALIDATION ERROR 
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            alert("Ada kesalahan input:\n" + @json($errors->all()).join("\n"));
        });
    @endif
</script>
@endpush