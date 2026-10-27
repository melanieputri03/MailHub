@extends('layout')
@section('title', 'Editor Header & Footer')

@section('content')

{{-- HEADER HALAMAN --}}
<div class="flex items-start justify-between gap-4 mb-5">
    <div>
        <h1 class="text-[18px] font-bold text-ink flex items-center gap-2">
            <svg class="w-5 h-5 text-maroon" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
            </svg>
            Editor Header & Footer
        </h1>
        <p class="text-[13px] text-muted mt-1 ml-7">
            Template: <span class="font-semibold text-ink">{{ $template->nama }}</span>
        </p>
    </div>

    <div class="flex gap-2">
        {{-- Tombol Simpan --}}
        <button type="button" onclick="simpanEditor()" id="btnSave"
                class="bg-gold hover:bg-goldD text-white text-[13px] font-semibold px-4 py-2.5 rounded-md
                       flex items-center gap-2 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            Simpan Desain
        </button>
        
        {{-- Tombol Kembali Edit Teks --}}
        <button type="button" onclick="bukaModalEditTeks()" 
                class="bg-white border border-outline text-[13px] text-ink px-4 py-2.5 rounded-md hover:bg-cream transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
            </svg>
            Edit Teks Body
        </button>
    </div>
</div>

{{-- INFO --}}
<div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 text-[12px] px-4 py-3 rounded-md flex items-start gap-2">
    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>
    </svg>
    <div>
        <strong>Cara pakai:</strong> Drag block dari panel kiri ke canvas. Klik elemen untuk edit warnanya.
    </div>
</div>

{{-- GRAPESJS EDITOR (TANPA TAB) --}}
<div class="bg-white border border-outline rounded-lg overflow-hidden flex flex-col" style="height: 750px;">
    <div class="flex flex-1 min-h-0">
        {{-- KIRI: BLOCKS --}}
        <div class="w-[200px] border-r border-outline bg-cream/30 overflow-y-auto flex-shrink-0">
            <div class="px-3 py-2 border-b border-outline bg-cream/60">
                <div class="text-[11px] font-bold text-muted uppercase tracking-wider">Blocks</div>
            </div>
            <div id="blocks" class="p-2"></div>
        </div>
        
        {{-- TENGAH: CANVAS --}}
        <div class="flex-1 min-w-0 bg-[#E2DFDB]"> 
            <div id="gjs" style="height: 100%;"></div>
        </div>
        
        {{-- KANAN: STYLE MANAGER --}}
        <div class="w-[250px] border-l border-outline bg-cream/30 overflow-y-auto flex-shrink-0">
            <div class="px-3 py-2 border-b border-outline bg-cream/60">
                <div class="text-[11px] font-bold text-muted uppercase tracking-wider">Style</div>
            </div>
            <div id="styles" class="p-2"></div>
        </div>
    </div>
</div>

{{-- FORM SUBMIT --}}
<form id="formEditor" method="POST" action="{{ route('template.editor.save', $template->template_email_id ?: 0) }}">
    @csrf
    <input type="hidden" name="header_html" id="inputHeaderHtml">
    <input type="hidden" name="footer_html" id="inputFooterHtml">
    <input type="hidden" name="header_css"  id="inputHeaderCss">
    <input type="hidden" name="footer_css"  id="inputFooterCss">
</form>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/grapesjs@0.21.13/dist/css/grapes.min.css">
<style>
    #gjs { height: 100%; }
    .gjs-one-bg { background-color: #F5ECEA; }
    .gjs-two-color { color: #803033; }
    .gjs-three-bg { background-color: #803033; color: white; }
    .gjs-four-color, .gjs-four-color-h:hover { color: #FF8C00; }
    .gjs-block { min-height: auto; padding: 8px; font-size: 12px; }

    .body-preview-locked {
        pointer-events: none; 
        user-select: none;    
        opacity: 0.8;         
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/grapesjs@0.21.13"></script>
<script>
    let editor;
    const logoUrl = @json(asset('images/logo-batamindo.jpg'));

    const DEFAULT_HEADER = `
        <table class="bic-header" width="100%" cellpadding="0" cellspacing="0" style="background:#F5ECEA; padding:24px 0 0 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px 8px 0 0; overflow:hidden;">
                        <tr>
                            <td style="padding:20px 24px; border-bottom:2px solid #803033;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="90">
                                            <img src="${logoUrl}" style="height:48px; display:block;">
                                        </td>
                                        <td style="padding-left:12px; vertical-align:middle;">
                                            <div style="color:#803033; font-weight:bold; font-size:16px;">BATAMINDO INDUSTRIAL PARK</div>
                                            <div style="color:#6B7280; font-size:11px;">PT BATAMINDO INVESTMENT CAKRAWALA</div>
                                        </td>
                                        <td align="right" style="vertical-align:top;">
                                            <div style="color:#202124; font-weight:bold; font-size:10px;">27 Oct 2026</div>
                                            <div style="color:#6B7280; font-size:10px;">Ref: BIC/2026</div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>`;

    const BODY_EMAIL = `
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#F5ECEA; padding:0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:0; overflow:hidden;">
                        <tr>
                            <td style="padding:32px 24px; min-height:280px;">
                                <h2 style="margin:0 0 20px; color:#803033; font-size:18px; letter-spacing:1px; text-align:center;">
                                    {{ $template->nama }}
                                </h2>
                                <div class="body-preview-locked" style="font-size:13px; line-height:1.75; color:#202124;">
                                    {!! $template->body ?: 'Belum ada isi body email. Silakan edit di halaman Kelola Template.' !!}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>`;

    const DEFAULT_FOOTER = `
        <table class="bic-footer" width="100%" cellpadding="0" cellspacing="0" style="background:#F5ECEA; padding:0 0 24px 0;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="background:#1a1a1a; border-radius:0 0 8px 8px; overflow:hidden;">
                        <tr>
                            <td style="padding:20px 24px;">
                                <div style="color:#ffffff; text-align:center; font-size:12px; font-weight:bold;">PT BATAMINDO INVESTMENT CAKRAWALA</div>
                                <div style="color:#9CA3AF; text-align:center; font-size:10px; margin-top:8px;">
                                    Wisma Batamindo Lt. 3, Jl. Rasamala No. 1<br>Mukakuning, Batam 29433, Indonesia
                                </div>
                                <div style="color:#9CA3AF; text-align:center; font-size:10px; margin-top:8px;">
                                    Telp: (0778) 431 888 &bull; Fax: (0778) 431 999<br>
                                    info@batamindo.co.id &bull; www.batamindo.co.id
                                </div>
                                <div style="text-align:center; margin-top:12px;">
                                    <a href="#" style="display:inline-block; width:24px; height:24px; background:#4b5563; border-radius:50%; color:white; text-align:center; line-height:24px; margin:0 4px; font-size:12px;">f</a>
                                    <a href="#" style="display:inline-block; width:24px; height:24px; background:#4b5563; border-radius:50%; color:white; text-align:center; line-height:24px; margin:0 4px; font-size:12px;">in</a>
                                    <a href="#" style="display:inline-block; width:24px; height:24px; background:#4b5563; border-radius:50%; color:white; text-align:center; line-height:24px; margin:0 4px; font-size:12px;">@</a>
                                </div>
                                <div style="color:#6B7280; text-align:center; font-size:9px; margin-top:12px;">
                                    &copy; 2026 BIC MailHub. All rights reserved.<br>
                                    Unsubscribe &bull; Privacy Policy
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>`;

    let savedHeader = @json($template->header_html ?: '');
    let savedFooter = @json($template->footer_html ?: '');
    let savedCss    = @json($template->header_css ?: '');

    let savedHtml = '';

    if (savedHeader.trim() || savedFooter.trim()) {
        const bodyPreview = `
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#F5ECEA; padding:0;">
                <tr>
                    <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:0; overflow:hidden;">
                            <tr>
                                <td style="padding:32px 24px; min-height:280px;">
                                    <h2 style="margin:0 0 20px; color:#803033; font-size:18px; letter-spacing:1px; text-align:center;">
                                        {{ $template->nama }}
                                    </h2>
                                    <div class="body-preview-locked" style="font-size:13px; line-height:1.75; color:#202124;">
                                        {!! $template->body ?: 'Belum ada isi body email.' !!}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>`;

        savedHtml = (savedHeader || '') + bodyPreview + (savedFooter || '');
        } else {
            savedHtml = ''; 
        }

    document.addEventListener('DOMContentLoaded', function () {
        editor = grapesjs.init({
            container: '#gjs',
            height: '100%',
            width: 'auto',
            storageManager: false,
            fromElement: false,
            blockManager: { appendTo: '#blocks' },
            styleManager: {
                appendTo: '#styles',
                sectors: [
                    { name: 'Dimensi', open: false, properties: ['width', 'height', 'max-width', 'min-height', 'margin', 'padding'] },
                    { name: 'Tipografi', open: false, properties: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align'] },
                    { name: 'Background', open: false, properties: ['background-color', 'background-image', 'background-repeat', 'background-position'] },
                    { name: 'Border', open: false, properties: ['border-width', 'border-style', 'border-color', 'border-radius'] },
                ],
            },
        });

        const bm = editor.BlockManager;
        bm.add('header-bic', { label: 'Header BIC', category: 'BIC Components', content: DEFAULT_HEADER });
        bm.add('footer-bic', { label: 'Footer BIC', category: 'BIC Components', content: DEFAULT_FOOTER });
        bm.add('body-preview', { label: 'Preview Body Email', category: 'BIC Components', content: BODY_EMAIL });

        editor.setComponents(savedHtml);
        if (savedCss.trim()) editor.setStyle(savedCss);
    });

    function simpanEditor() {
        if (!editor) return;

        const btn = document.getElementById('btnSave');
        btn.disabled = true;
        btn.innerHTML = 'Menyimpan...';

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = editor.getHtml();
        const fullCss = editor.getCss();

        const bodyToRemove = tempDiv.querySelector('.body-preview-locked');
        if (bodyToRemove && bodyToRemove.closest('table')) {
            bodyToRemove.closest('table').remove();
        }

        const headerEl = tempDiv.querySelector('.bic-header');
        const footerEl = tempDiv.querySelector('.bic-footer');
        
        if (footerEl) footerEl.remove(); 

        // 4. Set ke hidden input
        document.getElementById('inputHeaderHtml').value = headerEl ? headerEl.outerHTML : tempDiv.innerHTML;
        document.getElementById('inputFooterHtml').value = footerEl ? footerEl.outerHTML : '';
        document.getElementById('inputHeaderCss').value  = fullCss;
        document.getElementById('inputFooterCss').value  = fullCss;

        document.getElementById('formEditor').submit();
    }

    function bukaModalEditTeks() {
        const tplData = {
            template_email_id: {{ $template->template_email_id }},
            nama: @json($template->nama),
            deskripsi: @json($template->deskripsi),
            subject: @json($template->subject),
            body: @json($template->body),
            status: @json($template->status ?? 'active')
        };

        localStorage.setItem('edit_template_data', JSON.stringify(tplData));
        window.location.href = "{{ route('template.index') }}?edit=" + tplData.template_email_id;
    }
</script>
@endpush