@extends('layout')
@section('title', 'Kelola Template')

@section('content')

{{-- HEADER --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div class="flex-1 min-w-0">
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <path d="M14 2v6h6M8 13h8M8 17h5"/>
            </svg>
            Kelola Template
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">Tambah, edit, atau hapus template email.</p>
    </div>

    <button onclick="bukaModalTemplate(null)"
            class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-4 py-2.5 rounded-md flex items-center gap-2
                   transition shadow-sm flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14"/>
        </svg>
        Tambah Template
    </button>
</div>

{{--  SEARCH BAR  --}}
<div class="mb-4 flex items-center gap-3">
    <div class="relative flex-1">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
            </svg>
        </span>
        <input type="text" id="searchTemplate" oninput="filterTemplate()"
               placeholder="Cari template berdasarkan nama atau deskripsi..."
               class="w-full border border-outline rounded-md pl-10 pr-3 py-2.5 text-[13px] text-ink
                      focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition bg-white">
    </div>
    <div class="text-[12px] text-muted whitespace-nowrap">
        Total: <span class="font-semibold text-ink" id="totalTemplate">{{ $template->count() }}</span> template
    </div>
</div>

{{-- GRID TEMPLATE --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    @forelse($template as $tpl)
        <div class="template-card bg-white border border-outline rounded-lg overflow-hidden hover:shadow-lg transition flex flex-col"
             data-nama="{{ strtolower($tpl->nama) }}"
             data-deskripsi="{{ strtolower($tpl->deskripsi ?? '') }}"
             data-subject="{{ strtolower($tpl->subject ?? '') }}"
             data-status="{{ $tpl->status }}">

            {{-- PREVIEW MINI --}}
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

            {{-- INFO --}}
            <div class="px-4 pt-3 pb-2 flex-1">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="font-semibold text-[13px] text-ink">{{ $tpl->nama }}</h3>
                    @if($tpl->status === 'active')
                        <span class="bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-0.5 rounded font-semibold">
                            Aktif
                        </span>
                    @else
                        <span class="bg-cream text-muted border border-outline text-[10px] px-2 py-0.5 rounded font-semibold">
                            Nonaktif
                        </span>
                    @endif
                </div>
                <p class="text-[11px] text-muted leading-snug">{{ $tpl->deskripsi ?? '-' }}</p>
                @if($tpl->subject)
                    <p class="text-[10px] text-muted mt-1 truncate">Subject: {{ $tpl->subject }}</p>
                @endif
            </div>

            {{-- AKSI --}}
            <div class="px-4 pb-4 space-y-2">
                <div class="grid grid-cols-2 gap-2">
                    <button onclick='bukaModalTemplate(@json($tpl))'
                            class="text-center bg-white border border-outline text-ink text-[12px] font-semibold
                                   px-3 py-2 rounded-md hover:bg-cream transition">
                        ✏️ Edit
                    </button>
                    <button type="button"
                            onclick="bukaModalHapusTemplate({{ $tpl->template_email_id }}, '{{ addslashes($tpl->nama) }}')"
                            class="text-center bg-red-50 border border-red-200 text-red-600 text-[12px] font-semibold
                                   px-3 py-2 rounded-md hover:bg-red-100 transition">
                        🗑️ Hapus
                    </button>
                </div>
            </div>
        </div>

    @empty
        <div class="col-span-full bg-white border border-outline rounded-lg p-12 text-center">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-10 h-10 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6M8 13h8M8 17h5"/>
                </svg>
                <div class="text-[13px] text-muted">Belum ada template. Klik "Tambah Template" untuk membuat.</div>
            </div>
        </div>
    @endforelse

    <div id="templateEmpty" class="hidden col-span-full bg-white border border-outline rounded-lg p-12 text-center">
        <div class="flex flex-col items-center gap-3">
            <svg class="w-10 h-10 text-muted/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>
            </svg>
            <div class="text-[13px] text-muted">Template gak ditemukan. Coba keyword lain.</div>
            <button type="button" onclick="clearSearchTemplate()"
                    class="text-[12px] text-gold font-semibold hover:underline">
                Reset Pencarian
            </button>
        </div>
    </div>

</div>


{{-- MODAL TAMBAH/EDIT TEMPLATE --}}
<div id="modalTemplate" class="hidden fixed inset-0 bg-black/45 backdrop-blur-[1px] flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl border border-outline max-h-[90vh] overflow-y-auto">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-outline flex justify-between items-start sticky top-0 bg-white z-10">
            <div class="flex gap-3">
                <div class="w-10 h-10 bg-gold/10 rounded-md flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6M8 13h8M8 17h5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-[15px] text-ink" id="modalTitle">Tambah Template</h3>
                    <p class="text-[12px] text-muted mt-0.5">Isi data template email.</p>
                </div>
            </div>
            <button type="button" onclick="tutupModalTemplate()"
                    class="text-muted hover:text-ink w-7 h-7 flex items-center justify-center rounded hover:bg-cream transition">
                ✕
            </button>
        </div>

        {{-- FORM --}}
        <form id="formTemplate" method="POST" action="{{ route('template.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="template_id" id="formTemplateId" value="">

            <div class="px-6 py-5 space-y-4">

                {{-- Nama Template --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">
                        Nama Template <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="formNama" required maxlength="100"
                           placeholder="Misal: Pengumuman Agustusan"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">Deskripsi</label>
                    <input type="text" name="deskripsi" id="formDeskripsi" maxlength="255"
                           placeholder="Misal: Template untuk pengumuman resmi"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                {{-- Subject --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">Subject Email</label>
                    <input type="text" name="subject" id="formSubject" maxlength="255"
                           placeholder="Misal: [PENGUMUMAN] Informasi Penting"
                           class="w-full mt-1.5 border border-outline rounded-md px-3.5 py-2.5 text-[13px] text-ink
                                  focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition">
                </div>

                {{-- Body (TinyMCE) --}}
                <div>
                    <label class="text-[12px] font-semibold text-ink">Isi Email (Body)</label>
                    <div class="mt-1.5">
                        <textarea name="body" id="formBody"></textarea>
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
            <div class="px-6 py-4 border-t border-outline flex justify-end gap-3 sticky bottom-0 bg-white">
                <button type="button" onclick="tutupModalTemplate()"
                        class="bg-white border border-outline text-[13px] text-ink px-5 py-2.5 rounded-md hover:bg-cream transition">
                    Batal
                </button>
                <button type="submit"
                        class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-5 py-2.5 rounded-md
                               flex items-center gap-2 transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                    Lanjut Desain
                </button>
            </div>
        </form>
    </div>
</div>


{{-- MODAL KONFIRMASI HAPUS --}}
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
            <h3 class="font-bold text-[17px] text-ink mb-2">Hapus Template?</h3>

            {{-- Deskripsi --}}
            <p class="text-[13px] text-muted leading-relaxed">
                Anda akan menghapus template:
            </p>
            <p class="text-[14px] font-semibold text-ink mt-2 mb-2 px-4 py-2 bg-cream/60 rounded-md" id="hapusNamaTemplate">
                -
            </p>
            <p class="text-[12px] text-muted leading-relaxed">
                Data akan dihapus dari daftar. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        {{-- FORM HAPUS (hidden) --}}
        <form id="formHapusTemplate" method="POST" action="">
            @csrf
            @method('DELETE')
        </form>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t border-outline grid grid-cols-2 gap-3">
            <button type="button" onclick="tutupModalHapus()"
                    class="bg-white border border-outline text-[13px] text-ink px-5 py-3 rounded-md hover:bg-cream transition font-medium">
                Batal
            </button>
            <button type="submit" form="formHapusTemplate"
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

@push('styles')
<style>
    .tox .tox-editor-header {
        box-shadow: none !important;
        border-bottom: 1px solid #E0D5D2 !important;
    }
    .tox .tox-toolbar-overlord {
        background-color: #FAFAFA !important;
    }
    .tox .tox-statusbar {
        border-top: 1px solid #E0D5D2 !important;
        background-color: #FAFAFA !important;
    }
</style>
@endpush

@push('scripts')
<script>
    const baseUrlTemplate = "{{ url('/template') }}";
    let editorInstance = null;
    let editorInitialized = false;

    function initEditorTemplate() {
        if (editorInitialized) return;

        tinymce.init({
            selector: '#formBody',
            height: 300,
            menubar: false,
            branding: false,
            plugins: 'lists link image code',
            toolbar: 'undo redo | fontfamily fontsize | bold italic underline | bullist numlist | link image | removeformat',
            toolbar_mode: 'wrap',
            resize: false,
            content_style: 'body { font-family: Inter, sans-serif; font-size: 13px; line-height: 1.75; color: #202124; padding: 12px 16px; }',
            setup: function (editor) {
                editorInstance = editor;
            }
        });

        editorInitialized = true;
    }

    // MODAL TAMBAH/EDIT
    function bukaModalTemplate(data) {
        const modal = document.getElementById('modalTemplate');
        const form  = document.getElementById('formTemplate');
        const title = document.getElementById('modalTitle');

        initEditorTemplate();

        if (data) {
            title.textContent = 'Edit Template';

            if (data.template_email_id == 0 || data.template_email_id == null) {
                form.action = baseUrlTemplate; 
                document.getElementById('formMethod').value = 'POST';
            } else {
                form.action = baseUrlTemplate + '/' + data.template_email_id;
                document.getElementById('formMethod').value = 'PUT';
            }
            
            document.getElementById('formTemplateId').value = data.template_email_id || '';

            document.getElementById('formNama').value      = data.nama || '';
            document.getElementById('formDeskripsi').value = data.deskripsi || '';
            document.getElementById('formSubject').value   = data.subject || '';

            const status = data.status || 'active';
            document.getElementById('statusAktif').checked    = (status === 'active');
            document.getElementById('statusNonaktif').checked = (status === 'inactive');

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if (typeof tinymce !== 'undefined') {
                setTimeout(function() {
                    const editor = tinymce.get('formBody');
                    if (editor) {
                        editor.setContent(data.body || '');
                    }
                }, 500); 
            }

        } else {
            title.textContent = 'Tambah Template';
            form.action = baseUrlTemplate;
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formTemplateId').value = '';

            form.reset();
            document.getElementById('statusAktif').checked = true;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            if (typeof tinymce !== 'undefined') {
                setTimeout(function() {
                    const editor = tinymce.get('formBody');
                    if (editor) editor.setContent('');
                }, 500);
            }
        }
    }

    function tutupModalTemplate() {
        document.getElementById('modalTemplate').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('modalTemplate')?.addEventListener('click', function (e) {
        if (e.target === this) tutupModalTemplate();
    });

    // MODAL HAPUS
    function bukaModalHapusTemplate(id, nama) {
        const modal = document.getElementById('modalHapus');
        const form  = document.getElementById('formHapusTemplate');

        form.action = baseUrlTemplate + '/' + id;
        document.getElementById('hapusNamaTemplate').textContent = nama;

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

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            tutupModalTemplate();
            tutupModalHapus();
        }
    });

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            alert("Ada kesalahan input:\n" + @json($errors->all()).join("\n"));
        });
    @endif

    // FILTER TEMPLATE BERDASARKAN SEARCH
    function filterTemplate() {
        const keyword = document.getElementById('searchTemplate').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.template-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const nama      = card.dataset.nama || '';
            const deskripsi = card.dataset.deskripsi || '';
            const subject   = card.dataset.subject || '';

            const cocok = keyword === ''
                || nama.includes(keyword)
                || deskripsi.includes(keyword)
                || subject.includes(keyword);

            card.classList.toggle('hidden', !cocok);
            if (cocok) visibleCount++;
        });

        const emptyMsg = document.getElementById('templateEmpty');
        if (emptyMsg) {
            emptyMsg.classList.toggle('hidden', visibleCount > 0);
        }

        const totalEl = document.getElementById('totalTemplate');
        if (totalEl) {
            totalEl.textContent = visibleCount;
        }
    }

    // RESET SEARCH
    function clearSearchTemplate() {
        const input = document.getElementById('searchTemplate');
        if (input) {
            input.value = '';
            filterTemplate();
            input.focus();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const editId = urlParams.get('edit');

        if (editId) {
            const dataStr = localStorage.getItem('edit_template_data');
            if (dataStr) {
                const data = JSON.parse(dataStr);
                bukaModalTemplate(data);
                localStorage.removeItem('edit_template_data'); 

                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }
    });
</script>
@endpush