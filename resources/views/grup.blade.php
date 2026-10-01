@extends('layout')
@section('title', 'Kelola Grup')

@section('content')

{{-- HEADER --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="9" cy="8" r="3"/>
                <circle cx="17" cy="9" r="2.5"/>
                <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
            </svg>
            Kelola Grup
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Kelompokkan penerima lintas divisi untuk sasaran broadcast.</p>
    </div>

    <button onclick="bukaModalGrup(null)"
            class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-4 py-2.5 rounded-md flex items-center gap-2
                   transition shadow-sm flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Buat Grup Baru
    </button>
</div>

{{-- LIST GRUP --}}
<div class="space-y-3">

    @forelse($grup as $g)
        <div class="bg-white border border-outline rounded-lg p-5 hover:shadow-sm transition">
            <div class="flex justify-between items-start gap-4">

                {{-- KIRI: Info Grup --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-[14px] text-ink flex items-center gap-2">
                        <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <path d="M8 9h8M8 13h8M8 17h5"/>
                        </svg>
                        {{ $g->nama }}
                    </h3>
                    <p class="text-[12px] text-muted mt-1">{{ $g->deskripsi ?? 'Tanpa deskripsi.' }}</p>

                    {{-- Preview Anggota (5 pertama + "+N") --}}
                    @if($g->penerima->count() > 0)
                        <div class="mt-3 pt-3 border-t border-outline/60">
                            <div class="mb-2">
                                <span class="text-[11px] font-semibold text-muted uppercase tracking-wider">
                                    Anggota Terhubung:
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5">
                                @foreach($g->penerima->take(5) as $p)
                                    <span class="bg-cream text-muted border border-outline text-[11px] px-2.5 py-1 rounded font-medium">
                                        {{ $p->nama }}
                                    </span>
                                @endforeach

                                @if($g->penerima->count() > 5)
                                    <button type="button" onclick='bukaModalGrup(@json($g))'
                                            class="bg-gold/15 text-gold text-[11px] px-2.5 py-1 rounded font-bold hover:bg-gold/25 transition">
                                        +{{ $g->penerima->count() - 5 }} lainnya
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-3 pt-3 border-t border-outline/60">
                            <span class="text-[11px] text-muted italic">Belum ada anggota. Klik "Kelola Anggota" untuk menambahkan.</span>
                        </div>
                    @endif
                </div>

                {{-- KANAN: Badge & Aksi --}}
                <div class="text-right flex-shrink-0">
                    <span class="bg-gold/15 text-gold text-[11px] px-3 py-1 rounded-full font-bold">
                        {{ $g->penerima_count ?? 0 }} Anggota
                    </span>
                    <div class="mt-3 flex items-center gap-2 justify-end">
                        <button onclick='bukaModalGrup(@json($g))'
                                class="w-7 h-7 hover:bg-cream rounded flex items-center justify-center text-muted hover:text-gold transition"
                                title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                            </svg>
                        </button>

                        <button onclick="bukaModalHapusGrup({{ $g->grup_id }}, '{{ addslashes($g->nama) }}')"
                                class="w-7 h-7 hover:bg-red-50 rounded flex items-center justify-center text-muted hover:text-red-600 transition"
                                title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white border border-outline rounded-lg p-12 text-center">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-12 h-12 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="3"/>
                    <circle cx="17" cy="9" r="2.5"/>
                    <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
                </svg>
                <div class="text-[14px] font-semibold text-ink">Belum ada grup.</div>
                <div class="text-[12px] text-muted">Klik "Buat Grup Baru" untuk mulai mengelompokkan penerima.</div>
            </div>
        </div>
    @endforelse

</div>


{{-- MODAL BUAT/EDIT GRUP --}}
<div id="modalGrup" class="hidden fixed inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-5xl max-h-[93vh] overflow-hidden flex flex-col border border-outline">

        <div class="px-6 py-4 border-b border-outline flex justify-between items-start bg-white flex-shrink-0">
            <div class="flex gap-3">
                <div class="w-10 h-10 bg-gold/10 rounded-md flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="8" r="3"/>
                        <circle cx="17" cy="9" r="2.5"/>
                        <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-[15px] text-ink" id="modalGrupTitle">Buat Grup Baru</h3>
                    <p class="text-[12px] text-muted mt-0.5">Isi nama grup & pilih anggota penerima.</p>
                </div>
            </div>
            <button type="button" onclick="tutupModalGrup()"
                    class="text-muted hover:text-ink text-xl leading-none w-7 h-7 flex items-center justify-center rounded hover:bg-cream transition">
                ✕
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1 bg-white">
            <form id="formGrup" method="POST" action="{{ route('grup.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formGrupMethod" value="POST">
                <input type="hidden" name="grup_id" id="formGrupId" value="">

                <label class="text-[12px] font-semibold text-ink">
                    Nama Grup Penerima <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="formGrupNama" required
                       placeholder="Misal: Tenant Factory Managers"
                       class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                              focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition mb-4">

                <label class="text-[12px] font-semibold text-ink">Deskripsi</label>
                <textarea name="deskripsi" id="formGrupDeskripsi" rows="2"
                          placeholder="Opsional — keterangan grup"
                          class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                 focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition mb-5 resize-none"></textarea>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- PANEL KIRI --}}
                    <div class="border border-outline rounded-lg overflow-hidden">
                        <div class="px-4 py-3 border-b border-outline bg-cream/50 flex justify-between items-center">
                            <span class="text-[13px] font-semibold text-ink flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/>
                                    <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
                                </svg>
                                Pilih Anggota Tersedia
                            </span>
                            <span class="text-[11px] text-muted">{{ $penerima->count() }} Kontak</span>
                        </div>

                        <div class="p-3 border-b border-outline">
                            <div class="relative mb-3">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                                    </svg>
                                </span>
                                <input type="text" id="searchAnggota" oninput="filterAnggota()"
                                       placeholder="Cari nama, email, atau divisi..."
                                       class="w-full border border-outline rounded-md pl-9 pr-3 py-2 text-[12px] text-ink
                                              focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                            </div>
                        </div>

                        <div class="max-h-80 overflow-y-auto divide-y divide-outline/60" id="daftarAnggota">
                            @forelse($penerima as $p)
                                <label class="anggota-item flex items-start gap-3 px-3.5 py-3 hover:bg-gold/5 cursor-pointer transition"
                                       data-nama="{{ strtolower($p->nama) }}"
                                       data-email="{{ strtolower($p->email) }}"
                                       data-divisi="{{ strtolower($p->divisi->nama ?? '') }}">
                                    <input type="checkbox" name="penerima_ids[]" value="{{ $p->penerima_id }}"
                                           class="anggota-checkbox mt-1 w-4 h-4 rounded accent-gold cursor-pointer">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[13px] font-medium text-ink truncate">{{ $p->nama }}</div>
                                        <div class="text-[11px] text-muted truncate">{{ $p->email }}</div>
                                    </div>
                                    <span class="bg-cream text-muted text-[10px] px-2 py-0.5 rounded font-medium flex-shrink-0">
                                        {{ $p->divisi->nama ?? '-' }}
                                    </span>
                                </label>
                            @empty
                                <div class="px-4 py-8 text-center text-muted text-[12px]">
                                    Belum ada penerima. Tambahkan penerima dulu.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- PANEL KANAN --}}
                    <div class="border border-outline rounded-lg overflow-hidden bg-gold/[0.03]">
                        <div class="px-4 py-3 border-b border-outline bg-cream/50 flex justify-between items-center">
                            <span class="text-[13px] font-semibold text-ink flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Anggota Terpilih
                            </span>
                            <span class="bg-gold text-white text-[10px] px-2 py-0.5 rounded font-bold tracking-wider">
                                <span id="jumlahTerpilih">0</span> KONTAK
                            </span>
                        </div>

                        <div class="px-4 py-2 border-b border-outline flex justify-between items-center bg-white">
                            <span class="text-[11px] text-muted">Penerima yang dipilih:</span>
                            <button type="button" onclick="hapusSemuaTerpilih()"
                                    class="text-[11px] text-red-500 font-semibold hover:text-red-700">
                                ✕ Hapus Semua
                            </button>
                        </div>

                        <div class="max-h-72 overflow-y-auto p-3 space-y-2" id="listTerpilih">
                            <div class="text-center text-[12px] text-muted py-6" id="emptyTerpilih">
                                Belum ada anggota terpilih.
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-outline bg-white flex items-center justify-between gap-3 flex-shrink-0">
            <span class="text-[11px] text-muted flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="m20 6-9 9-5-5"/>
                </svg>
                Grup akan langsung tersinkronisasi.
            </span>

            <div class="flex gap-3">
                <button type="button" onclick="tutupModalGrup()"
                        class="bg-white border border-outline text-[13px] text-ink px-5 py-2.5 rounded-md hover:bg-cream transition">
                    Batal
                </button>
                <button type="submit" form="formGrup"
                        class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-5 py-2.5 rounded-md
                               flex items-center gap-2 transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <path d="M17 21v-8H7v8M7 3v5h8"/>
                    </svg>
                    Simpan Grup
                </button>
            </div>
        </div>
    </div>
</div>


{{-- MODAL HAPUS GRUP --}}
<div id="modalHapusGrup" class="hidden fixed inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md border border-outline">

        <div class="px-6 py-6 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M12 3 2 21h20L12 3z"/>
                    <path d="M12 9v5M12 17h.01"/>
                </svg>
            </div>

            <h3 class="font-bold text-[17px] text-ink mb-2">Hapus Grup?</h3>

            <p class="text-[13px] text-muted leading-relaxed">Anda akan menghapus grup:</p>
            <p class="text-[14px] font-semibold text-ink mt-2 mb-2 px-4 py-2 bg-cream/60 rounded-md" id="hapusNamaGrup">-</p>
            <p class="text-[12px] text-muted leading-relaxed">
                Data akan dihapus dari daftar. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <form id="formHapusGrup" method="POST" action="">
            @csrf
            @method('DELETE')
        </form>

        <div class="px-6 py-4 border-t border-outline grid grid-cols-2 gap-3">
            <button type="button" onclick="tutupModalHapusGrup()"
                    class="bg-white border border-outline text-[13px] text-ink px-5 py-3 rounded-md hover:bg-cream transition font-medium">
                Batal
            </button>
            <button type="submit" form="formHapusGrup"
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
    const baseUrlGrup = "{{ url('/grup') }}";
    let selectedPenerima = new Set();

    // ==================== MODAL GRUP ====================
    function bukaModalGrup(data) {
        const modal = document.getElementById('modalGrup');
        const form  = document.getElementById('formGrup');
        const title = document.getElementById('modalGrupTitle');

        resetModalGrup();

        if (data) {
            title.textContent = 'Edit Grup';
            form.action = baseUrlGrup + '/' + data.grup_id;
            document.getElementById('formGrupMethod').value = 'PUT';
            document.getElementById('formGrupId').value = data.grup_id;
            document.getElementById('formGrupNama').value = data.nama;
            document.getElementById('formGrupDeskripsi').value = data.deskripsi || '';

            if (data.penerima && Array.isArray(data.penerima)) {
                data.penerima.forEach(p => {
                    selectedPenerima.add(p.penerima_id);
                });
                updateCheckboxFromState();
                updatePanelTerpilih();
            }
        } else {
            title.textContent = 'Buat Grup Baru';
            form.action = baseUrlGrup;
            document.getElementById('formGrupMethod').value = 'POST';
            document.getElementById('formGrupId').value = '';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalGrup() {
        document.getElementById('modalGrup').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function resetModalGrup() {
        document.getElementById('formGrup').reset();
        selectedPenerima.clear();
        document.querySelectorAll('.anggota-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.anggota-item').forEach(item => item.classList.remove('hidden'));
        document.getElementById('searchAnggota').value = '';
        updatePanelTerpilih();
    }

    // ==================== CHECKBOX ANGGOTA ====================
    document.querySelectorAll('.anggota-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const id = parseInt(this.value);
            if (this.checked) {
                selectedPenerima.add(id);
            } else {
                selectedPenerima.delete(id);
            }
            updatePanelTerpilih();
        });
    });

    function updateCheckboxFromState() {
        document.querySelectorAll('.anggota-checkbox').forEach(cb => {
            const id = parseInt(cb.value);
            cb.checked = selectedPenerima.has(id);
        });
    }

    // ==================== FILTER ANGGOTA ====================
    function filterAnggota() {
        const keyword = document.getElementById('searchAnggota').value.toLowerCase().trim();
        document.querySelectorAll('.anggota-item').forEach(item => {
            const nama = item.dataset.nama;
            const email = item.dataset.email;
            const divisi = item.dataset.divisi;
            const match = keyword === '' || nama.includes(keyword) || email.includes(keyword) || divisi.includes(keyword);
            item.classList.toggle('hidden', !match);
        });
    }

    // ==================== UPDATE PANEL TERPILIH ====================
    function updatePanelTerpilih() {
        const listEl = document.getElementById('listTerpilih');
        const countEl = document.getElementById('jumlahTerpilih');

        countEl.textContent = selectedPenerima.size;

        if (selectedPenerima.size === 0) {
            listEl.innerHTML = '<div class="text-center text-[12px] text-muted py-6" id="emptyTerpilih">Belum ada anggota terpilih.</div>';
            return;
        }

        let html = '';
        document.querySelectorAll('.anggota-checkbox:checked').forEach(cb => {
            const item = cb.closest('.anggota-item');
            const nama = item.querySelector('div > div:first-child').textContent.trim();
            const email = item.querySelector('div > div:last-child').textContent.trim();
            const divisi = item.querySelector('span:last-child').textContent.trim();

            html += `
                <div class="bg-white border border-outline rounded-md px-3 py-2 flex items-center justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <div class="text-[12px] font-semibold text-ink truncate">${nama}</div>
                        <div class="text-[10px] text-muted truncate">${email}</div>
                    </div>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <span class="bg-cream text-muted text-[10px] px-1.5 py-0.5 rounded font-bold">${divisi}</span>
                        <button type="button" onclick="hapusAnggota(${cb.value})" class="text-red-400 hover:text-red-600 w-5 h-5 flex items-center justify-center rounded hover:bg-red-50 transition">✕</button>
                    </div>
                </div>
            `;
        });

        listEl.innerHTML = html;
    }

    function hapusAnggota(id) {
        selectedPenerima.delete(parseInt(id));
        document.querySelectorAll('.anggota-checkbox').forEach(cb => {
            if (parseInt(cb.value) === parseInt(id)) {
                cb.checked = false;
            }
        });
        updatePanelTerpilih();
    }

    function hapusSemuaTerpilih() {
        selectedPenerima.clear();
        document.querySelectorAll('.anggota-checkbox').forEach(cb => cb.checked = false);
        updatePanelTerpilih();
    }

    // ==================== MODAL HAPUS GRUP ====================
    function bukaModalHapusGrup(id, nama) {
        const modal = document.getElementById('modalHapusGrup');
        const form  = document.getElementById('formHapusGrup');
        form.action = baseUrlGrup + '/' + id;
        document.getElementById('hapusNamaGrup').textContent = nama;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function tutupModalHapusGrup() {
        document.getElementById('modalHapusGrup').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ==================== CLOSE MODAL ====================
    ['modalGrup', 'modalHapusGrup'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function (e) {
            if (e.target === this) {
                if (id === 'modalGrup') tutupModalGrup();
                else tutupModalHapusGrup();
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            tutupModalGrup();
            tutupModalHapusGrup();
        }
    });
</script>
@endpush