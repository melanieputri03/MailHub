@extends('layout')
@section('title', 'Buat Email')

@section('content')

{{-- HEADER --}}
<div class="mb-5">
    <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
        <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
        </svg>
        Buat Email
    </h1>
    <p class="text-[13px] text-muted mt-1 ml-7">Pilih mode, edit isi email, lalu pilih penerima.</p>
</div>

{{-- STEP INDICATOR --}}
<div class="flex items-center gap-3 mb-6 text-[12px]">
    <div id="step-indicator-1" class="flex items-center gap-2">
        <span class="w-5 h-5 rounded-full bg-gold text-white flex items-center justify-center font-bold text-[10px]">1</span>
        <span class="font-medium text-ink">Pilih Mode</span>
    </div>
    <svg class="w-3 h-3 text-muted/50" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M5 12h14M13 6l6 6-6 6"/>
    </svg>
    <div id="step-indicator-2" class="flex items-center gap-2 opacity-40">
        <span class="w-5 h-5 rounded-full bg-muted text-white flex items-center justify-center font-bold text-[10px]">2</span>
        <span class="font-medium">Pilih Template</span>
    </div>
    <svg class="w-3 h-3 text-muted/50" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path d="M5 12h14M13 6l6 6-6 6"/>
    </svg>
    <div id="step-indicator-3" class="flex items-center gap-2 opacity-40">
        <span class="w-5 h-5 rounded-full bg-muted text-white flex items-center justify-center font-bold text-[10px]">3</span>
        <span class="font-medium">Edit &amp; Penerima</span>
    </div>
</div>

{{-- STEP 1: PILIH MODE --}}
<div id="step-1">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <button type="button" onclick="pilihMode('template')"
                class="text-left bg-white border border-outline rounded-xl p-8 hover:border-gold hover:shadow-lg transition group">
            <div class="w-16 h-16 bg-maroon/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-maroon transition">
                <svg class="w-8 h-8 text-maroon group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6M8 13h8M8 17h5"/>
                </svg>
            </div>
            <h3 class="text-[17px] font-bold text-ink mb-3">📄 Gunakan Template</h3>
            <p class="text-[13px] text-muted leading-relaxed mb-6">
                Gunakan desain email yang sudah disediakan. Tinggal edit isi & subject, siap kirim.
            </p>
            <div class="inline-flex items-center gap-2 text-gold font-semibold text-[13px]">
                Pilih Template
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </div>
        </button>

        <button type="button" onclick="pilihMode('manual')"
                class="text-left bg-white border border-outline rounded-xl p-8 hover:border-gold hover:shadow-lg transition group">
            <div class="w-16 h-16 bg-gold/10 rounded-xl flex items-center justify-center mb-5 group-hover:bg-gold transition">
                <svg class="w-8 h-8 text-gold group-hover:text-white transition" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                </svg>
            </div>
            <h3 class="text-[17px] font-bold text-ink mb-3">✏️ Buat dari Awal</h3>
            <p class="text-[13px] text-muted leading-relaxed mb-6">
                Buat isi email sendiri. Header & footer BIC tetap ditambahkan otomatis.
            </p>
            <div class="inline-flex items-center gap-2 text-gold font-semibold text-[13px]">
                Buat Manual
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </div>
        </button>
    </div>
</div>

{{-- STEP 2: PILIH TEMPLATE --}}
<div id="step-2" class="hidden">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-[15px] font-bold text-ink">Pilih Template Email</h2>
            <p class="text-[12px] text-muted mt-0.5">Pilih salah satu template untuk mulai mengedit.</p>
        </div>
        <button type="button" onclick="kembaliKeStep(1)"
                class="bg-white border border-outline text-[12px] text-ink px-3 py-2 rounded-md hover:bg-cream transition flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5M11 6l-6 6 6 6"/>
            </svg>
            Kembali
        </button>
    </div>

    @if($template->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($template as $tpl)
                <div class="bg-white border border-outline rounded-lg overflow-hidden hover:shadow-lg hover:border-gold transition flex flex-col">

                    <div class="bg-cream/40 p-3">
                        <div class="bg-white rounded-sm border border-outline/60 overflow-hidden h-[280px] flex flex-col shadow-sm">
                            <div class="bg-white px-3 py-2 border-b-2 border-maroon">
                                <div class="flex items-center gap-1.5">
                                    <img src="{{ asset('images/logo-batamindo.jpg') }}"
                                         alt="BIC"
                                         class="w-4 h-4 object-contain flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-maroon font-bold text-[5px] leading-tight truncate">BATAMINDO INDUSTRIAL PARK</div>
                                        <div class="text-muted text-[4px] leading-tight truncate">PT BATAMINDO INVESTMENT</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 p-3 overflow-hidden">
                                <div class="text-center mb-2">
                                    <div class="text-[8px] font-bold text-maroon uppercase tracking-wider">{{ $tpl->nama }}</div>
                                </div>
                                <div class="text-[6px] text-gray-700 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($tpl->body ?? ''), 180) }}
                                </div>
                            </div>

                            <div class="bg-[#1a1a1a] px-2 py-2">
                                <div class="text-center text-[4px] text-white font-bold mb-0.5">PT BATAMINDO INVESTMENT</div>
                                <div class="text-center text-[3.5px] text-gray-400 leading-tight">
                                    Wisma Batamindo Lt. 3<br>
                                    Telp: (0778) 431 888
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 pt-3 pb-2 flex-1">
                        <h3 class="font-semibold text-[13px] text-ink mb-1">{{ $tpl->nama }}</h3>
                        <p class="text-[11px] text-muted leading-snug">{{ $tpl->deskripsi }}</p>
                    </div>

                    <div class="px-4 pb-4">
                        <button type="button" onclick='pilihTemplate(@json($tpl))'
                                class="block w-full text-center bg-gold hover:bg-goldD text-white text-[13px] font-semibold
                                       px-4 py-2.5 rounded-md transition shadow-sm">
                            Pilih Template
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-outline rounded-lg p-12 text-center">
            <div class="text-[13px] text-muted">Belum ada template. Silakan seed template dulu.</div>
        </div>
    @endif
</div>


{{-- STEP 3: EDIT EMAIL + PILIH PENERIMA --}}
<div id="step-3" class="hidden">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-[15px] font-bold text-ink" id="step3-title">Edit Email</h2>
            <p class="text-[12px] text-muted mt-0.5">Edit subject &amp; isi email, lalu pilih penerima.</p>
        </div>
        <button type="button" onclick="kembaliKeStep(1)"
                class="bg-white border border-outline text-[12px] text-ink px-3 py-2 rounded-md hover:bg-cream transition flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5M11 6l-6 6 6 6"/>
            </svg>
            Ganti Mode
        </button>
    </div>

    <form id="formEmail" method="POST" action="{{ route('email.preview') }}">
        @csrf
        <input type="hidden" name="template_id" id="formTemplateId" value="">

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <div class="lg:col-span-3 space-y-5">

                <div class="bg-white rounded-lg border border-outline p-5">
                    <label class="text-[12px] font-semibold text-ink">
                        Nama / Judul Email (Internal) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="inputNama" required
                           oninput="updatePreview()"
                           placeholder="Misal: Pengumuman Agustusan"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                    <p class="text-[11px] text-muted mt-1.5">Hanya untuk arsip internal.</p>
                </div>

                <div class="bg-white rounded-lg border border-outline p-5">
                    <label class="text-[12px] font-semibold text-ink">
                        Subject Email <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="inputSubject" required
                           oninput="updatePreview()"
                           placeholder="Subject email..."
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                <div class="bg-white rounded-lg border border-outline p-5">
                    <label class="text-[12px] font-semibold text-ink">
                        Isi Email <span class="text-red-500">*</span>
                    </label>

                    <div class="border border-outline rounded-md mt-1.5 overflow-hidden">
                        <div class="bg-cream/70 border-b border-outline px-3 py-1.5 flex flex-wrap items-center gap-0.5">
                            <button type="button" class="w-7 h-7 hover:bg-white rounded text-ink font-bold text-[13px]">B</button>
                            <button type="button" class="w-7 h-7 hover:bg-white rounded text-ink italic text-[13px]">I</button>
                            <button type="button" class="w-7 h-7 hover:bg-white rounded text-ink underline text-[13px]">U</button>
                            <span class="w-px h-5 bg-outline mx-1"></span>
                        </div>

                        <textarea name="body" id="inputBody" required
                                  oninput="updatePreview()"
                                  rows="12"
                                  placeholder="Tulis isi email di sini..."
                                  class="w-full px-4 py-3 text-[13px] text-ink leading-relaxed
                                         focus:outline-none resize-none bg-white"></textarea>
                    </div>
                </div>

                {{-- PILIH PENERIMA --}}
                <div class="bg-white rounded-lg border border-outline p-5">
                    <h3 class="text-[13px] font-semibold text-ink mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/>
                            <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
                        </svg>
                        Pilih Penerima
                    </h3>

                    <div class="space-y-3">

                        <label class="block p-4 border border-outline rounded-md hover:border-gold cursor-pointer transition">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="penerima_mode" value="semua"
                                       onchange="switchMode('semua')" class="mt-0.5 accent-gold">
                                <div class="flex-1">
                                    <div class="font-semibold text-[13px] text-ink">Semua Penerima Aktif</div>
                                    <div class="text-[11px] text-muted mt-0.5">Total {{ $penerima->count() }} kontak terdaftar</div>
                                </div>
                                <span class="bg-cream text-muted text-[11px] px-2.5 py-1 rounded font-bold flex-shrink-0">
                                    {{ $penerima->count() }}
                                </span>
                            </div>
                        </label>

                        <div class="border border-outline rounded-md">
                            <label class="block p-4 cursor-pointer">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="penerima_mode" value="divisi"
                                           onchange="switchMode('divisi')" class="mt-0.5 accent-gold">
                                    <div class="flex-1">
                                        <div class="font-semibold text-[13px] text-ink flex items-center gap-2">
                                            Berdasarkan Divisi
                                            <span class="bg-gold/15 text-gold text-[10px] px-1.5 py-0.5 rounded font-bold">MULTI</span>
                                        </div>
                                        <div class="text-[11px] text-muted mt-0.5">Pilih satu atau beberapa divisi</div>
                                    </div>
                                </div>
                            </label>

                            <div id="panel-divisi" class="hidden border-t border-outline bg-cream/30 p-4">
                                <label class="flex items-center gap-2 mb-3 pb-3 border-b border-outline/60 cursor-pointer">
                                    <input type="checkbox" id="pilih-semua-divisi"
                                           onchange="pilihSemuaDivisi(this.checked)"
                                           class="w-4 h-4 rounded accent-gold">
                                    <span class="text-[12px] font-semibold text-ink">Pilih semua divisi</span>
                                </label>

                                <div class="space-y-1.5">
                                    @foreach($divisi as $d)
                                        <label class="flex items-center gap-3 px-3 py-2 rounded hover:bg-white cursor-pointer transition">
                                            <input type="checkbox" name="divisi_ids[]" value="{{ $d->divisi_id }}"
                                                   data-jumlah="{{ $d->penerima()->where('status','active')->count() }}"
                                                   onchange="hitungTotal()"
                                                   class="divisi-checkbox w-4 h-4 rounded accent-gold">
                                            <span class="text-[12px] text-ink flex-1">{{ $d->nama }}</span>
                                            <span class="text-[11px] text-muted bg-white px-2 py-0.5 rounded border border-outline">
                                                {{ $d->penerima()->where('status','active')->count() }} orang
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="border border-outline rounded-md">
                            <label class="block p-4 cursor-pointer">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="penerima_mode" value="grup"
                                           onchange="switchMode('grup')" class="mt-0.5 accent-gold">
                                    <div class="flex-1">
                                        <div class="font-semibold text-[13px] text-ink flex items-center gap-2">
                                            Berdasarkan Grup
                                            <span class="bg-gold/15 text-gold text-[10px] px-1.5 py-0.5 rounded font-bold">MULTI</span>
                                        </div>
                                        <div class="text-[11px] text-muted mt-0.5">Pilih satu atau beberapa grup</div>
                                    </div>
                                </div>
                            </label>

                            <div id="panel-grup" class="hidden border-t border-outline bg-cream/30 p-4">
                                <label class="flex items-center gap-2 mb-3 pb-3 border-b border-outline/60 cursor-pointer">
                                    <input type="checkbox" id="pilih-semua-grup"
                                           onchange="pilihSemuaGrup(this.checked)"
                                           class="w-4 h-4 rounded accent-gold">
                                    <span class="text-[12px] font-semibold text-ink">Pilih semua grup</span>
                                </label>

                                <div class="space-y-1.5">
                                    @foreach($grup as $g)
                                        <label class="flex items-center gap-3 px-3 py-2 rounded hover:bg-white cursor-pointer transition">
                                            <input type="checkbox" name="grup_ids[]" value="{{ $g->grup_id }}"
                                                   data-jumlah="{{ $g->penerima_count }}"
                                                   onchange="hitungTotal()"
                                                   class="grup-checkbox w-4 h-4 rounded accent-gold">
                                            <span class="text-[12px] text-ink flex-1">{{ $g->nama }}</span>
                                            <span class="text-[11px] text-muted bg-white px-2 py-0.5 rounded border border-outline">
                                                {{ $g->penerima_count }} orang
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                @if($grup->count() === 0)
                                    <div class="text-center text-[12px] text-muted py-4">
                                        Belum ada grup. Buat grup dulu di halaman Kelola Grup.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="border border-outline rounded-md">
                            <label class="block p-4 cursor-pointer">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="penerima_mode" value="manual"
                                           onchange="switchMode('manual')" class="mt-0.5 accent-gold">
                                    <div class="flex-1">
                                        <div class="font-semibold text-[13px] text-ink">Penerima Tertentu</div>
                                        <div class="text-[11px] text-muted mt-0.5">Pilih satu per satu</div>
                                    </div>
                                </div>
                            </label>

                            <div id="panel-manual" class="hidden border-t border-outline bg-cream/30 p-4">
                                <div class="relative mb-3">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
                                        </svg>
                                    </span>
                                    <input type="text" id="searchPenerima" oninput="filterPenerimaManual()"
                                           placeholder="Cari nama atau email..."
                                           class="w-full border border-outline rounded-md pl-9 pr-3 py-2 text-[12px] text-ink
                                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition bg-white">
                                </div>

                                <div class="max-h-48 overflow-y-auto border border-outline rounded-md bg-white divide-y divide-outline/60">
                                    @foreach($penerima as $p)
                                        <label class="manual-item flex items-center gap-3 px-3 py-2 hover:bg-cream/50 cursor-pointer"
                                               data-nama="{{ strtolower($p->nama) }}"
                                               data-email="{{ strtolower($p->email) }}">
                                            <input type="checkbox" name="penerima_ids[]" value="{{ $p->penerima_id }}"
                                                   onchange="hitungTotal()"
                                                   class="manual-checkbox w-4 h-4 rounded accent-gold">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-[12px] font-medium text-ink truncate">{{ $p->nama }}</div>
                                                <div class="text-[10px] text-muted truncate">{{ $p->email }}</div>
                                            </div>
                                            <span class="bg-cream text-muted text-[10px] px-2 py-0.5 rounded">{{ $p->divisi->nama ?? '-' }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-outline flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gold/15 flex items-center justify-center">
                                <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/>
                                    <path d="M3.5 19a5.5 5.5 0 0 1 11 0M14 19a4.5 4.5 0 0 1 6.5-4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-muted uppercase tracking-wider">Total Penerima</div>
                                <div class="text-[16px] font-bold text-ink">
                                    <span id="total-penerima">0</span>
                                    <span class="text-[11px] text-muted font-normal ml-1">orang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-6 py-3 rounded-md
                                   flex items-center gap-2 transition shadow-sm">
                        Lanjut Preview
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="sticky top-6">

                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[11px] font-bold text-muted uppercase tracking-wider">Live Preview Email</p>
                        <span class="bg-gold text-white text-[10px] px-2 py-0.5 rounded font-bold tracking-wider">LIVE</span>
                    </div>

                    <div class="bg-white border border-outline rounded-lg overflow-hidden shadow-sm">
                        @include('partials.email-header')

                        <div class="px-6 py-6 min-h-[280px]">
                            <div class="text-center mb-4">
                                <h2 class="text-[15px] font-bold text-maroon uppercase tracking-wide" id="previewNama">
                                    EMAIL BARU
                                </h2>
                            </div>
                            <div id="previewBody" class="text-[12px] text-ink leading-relaxed whitespace-pre-line">
                                Isi email akan tampil di sini...
                            </div>
                        </div>

                        @include('partials.email-footer')
                    </div>

                    <div class="mt-3 bg-cream/60 border border-outline rounded-md p-3">
                        <div class="text-[10px] text-muted uppercase tracking-wider mb-1">Subject:</div>
                        <div id="previewSubject" class="text-[12px] font-semibold text-ink">(Belum diisi)</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    let currentStep = 1;

    function showStep(step) {
        currentStep = step;
        document.getElementById('step-1').classList.toggle('hidden', step !== 1);
        document.getElementById('step-2').classList.toggle('hidden', step !== 2);
        document.getElementById('step-3').classList.toggle('hidden', step !== 3);
        updateStepIndicator();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateStepIndicator() {
        [1, 2, 3].forEach(i => {
            const ind = document.getElementById('step-indicator-' + i);
            const circle = ind.querySelector('span:first-child');
            const label = ind.querySelector('span:last-child');

            if (i < currentStep) {
                ind.classList.remove('opacity-40');
                circle.className = 'w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-[10px]';
                circle.textContent = '✓';
                label.className = 'font-medium text-green-600';
            } else if (i === currentStep) {
                ind.classList.remove('opacity-40');
                circle.className = 'w-5 h-5 rounded-full bg-gold text-white flex items-center justify-center font-bold text-[10px]';
                circle.textContent = i;
                label.className = 'font-medium text-ink';
            } else {
                ind.classList.add('opacity-40');
                circle.className = 'w-5 h-5 rounded-full bg-muted text-white flex items-center justify-center font-bold text-[10px]';
                circle.textContent = i;
                label.className = 'font-medium';
            }
        });
    }

    function kembaliKeStep(step) { showStep(step); }

    function pilihMode(mode) {
        if (mode === 'template') {
            showStep(2);
        } else {
            prepareStep3({ id: null, nama: 'Manual', subject: '', body: '' });
            showStep(3);
        }
    }

    function pilihTemplate(tpl) {
        prepareStep3(tpl);
        showStep(3);
    }

    function prepareStep3(tpl) {
        document.getElementById('formTemplateId').value = tpl.id || '';
        document.getElementById('inputNama').value    = tpl.nama === 'Manual' ? '' : (tpl.nama || '');
        document.getElementById('inputSubject').value = tpl.subject || '';
        document.getElementById('inputBody').value    = tpl.body || '';

        document.getElementById('step3-title').textContent = tpl.id
            ? 'Edit Email — ' + tpl.nama
            : 'Buat Email dari Awal';

        updatePreview();
    }

    function updatePreview() {
        const nama    = document.getElementById('inputNama').value;
        const subject = document.getElementById('inputSubject').value;
        const body    = document.getElementById('inputBody').value;

        document.getElementById('previewNama').textContent = nama.trim().toUpperCase() || 'EMAIL BARU';
        document.getElementById('previewSubject').textContent = subject || '(Belum diisi)';
        document.getElementById('previewBody').textContent = body || 'Isi email akan tampil di sini...';
    }

    function switchMode(mode) {
        document.getElementById('panel-divisi').classList.add('hidden');
        document.getElementById('panel-grup').classList.add('hidden');
        document.getElementById('panel-manual').classList.add('hidden');

        if (mode === 'divisi') document.getElementById('panel-divisi').classList.remove('hidden');
        else if (mode === 'grup') document.getElementById('panel-grup').classList.remove('hidden');
        else if (mode === 'manual') document.getElementById('panel-manual').classList.remove('hidden');

        hitungTotal();
    }

    function pilihSemuaDivisi(checked) {
        document.querySelectorAll('.divisi-checkbox').forEach(cb => cb.checked = checked);
        hitungTotal();
    }

    function pilihSemuaGrup(checked) {
        document.querySelectorAll('.grup-checkbox').forEach(cb => cb.checked = checked);
        hitungTotal();
    }

    function filterPenerimaManual() {
        const keyword = document.getElementById('searchPenerima').value.toLowerCase().trim();
        document.querySelectorAll('.manual-item').forEach(item => {
            const cocok = keyword === ''
                || item.dataset.nama.includes(keyword)
                || item.dataset.email.includes(keyword);
            item.classList.toggle('hidden', !cocok);
        });
    }

    function hitungTotal() {
        const mode = document.querySelector('input[name="penerima_mode"]:checked')?.value;
        let total = 0;

        if (mode === 'semua') {
            total = {{ $penerima->count() }};
        } else if (mode === 'divisi') {
            document.querySelectorAll('.divisi-checkbox:checked').forEach(cb => {
                total += parseInt(cb.dataset.jumlah) || 0;
            });
        } else if (mode === 'grup') {
            document.querySelectorAll('.grup-checkbox:checked').forEach(cb => {
                total += parseInt(cb.dataset.jumlah) || 0;
            });
        } else if (mode === 'manual') {
            total = document.querySelectorAll('.manual-checkbox:checked').length;
        }

        document.getElementById('total-penerima').textContent = total.toLocaleString('id-ID');
        syncPilihSemua();
    }

    function syncPilihSemua() {
        const totalDiv = document.querySelectorAll('.divisi-checkbox').length;
        const checkedDiv = document.querySelectorAll('.divisi-checkbox:checked').length;
        const cbAllDiv = document.getElementById('pilih-semua-divisi');
        if (cbAllDiv) cbAllDiv.checked = (totalDiv > 0 && totalDiv === checkedDiv);

        const totalGrup = document.querySelectorAll('.grup-checkbox').length;
        const checkedGrup = document.querySelectorAll('.grup-checkbox:checked').length;
        const cbAllGrup = document.getElementById('pilih-semua-grup');
        if (cbAllGrup) cbAllGrup.checked = (totalGrup > 0 && totalGrup === checkedGrup);
    }

    // INIT 
    document.addEventListener('DOMContentLoaded', function () {
        @if(isset($draft) && $draft)
            // LOAD DRAFT DARI SESSION 
            document.getElementById('formTemplateId').value = '{{ $draft["template_id"] ?? "" }}';
            document.getElementById('inputNama').value      = @json($draft['nama'] ?? '');
            document.getElementById('inputSubject').value   = @json($draft['subject'] ?? '');
            document.getElementById('inputBody').value      = @json($draft['body'] ?? '');

            const mode = '{{ $draft["penerima_mode"] ?? "semua" }}';
            const radio = document.querySelector(`input[name="penerima_mode"][value="${mode}"]`);
            if (radio) radio.checked = true;
            switchMode(mode);

            @if(!empty($draft['divisi_ids']))
                @foreach($draft['divisi_ids'] as $id)
                    { const el = document.querySelector('.divisi-checkbox[value="{{ $id }}"]'); if (el) el.checked = true; }
                @endforeach
            @endif

            @if(!empty($draft['grup_ids']))
                @foreach($draft['grup_ids'] as $id)
                    { const el = document.querySelector('.grup-checkbox[value="{{ $id }}"]'); if (el) el.checked = true; }
                @endforeach
            @endif

            @if(!empty($draft['penerima_ids']))
                @foreach($draft['penerima_ids'] as $id)
                    { const el = document.querySelector('.manual-checkbox[value="{{ $id }}"]'); if (el) el.checked = true; }
                @endforeach
            @endif

            document.getElementById('step3-title').textContent = 'Edit Email';
            showStep(3);
            updatePreview();
            hitungTotal();

        @elseif($selectedTemplate)
            prepareStep3({
                id: {{ $selectedTemplate->id }},
                nama: @json($selectedTemplate->nama),
                subject: @json($selectedTemplate->subject),
                body: @json($selectedTemplate->body),
            });
            showStep(3);
            hitungTotal();

        @else
            showStep(1);
            hitungTotal();
        @endif
    });
</script>
@endpush