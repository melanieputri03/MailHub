<?php

namespace App\Http\Controllers;

use App\Models\TemplateEmail;
use Illuminate\Http\Request;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class TemplateController extends Controller
{
    public function index()
    {
        $template = TemplateEmail::orderBy('template_email_id', 'desc')->get();
        return view('template.index', compact('template'));
    }

    // SIMPAN DATA DASAR KE SESSION (BELUM KE DB)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'subject'   => 'nullable|string|max:255',
            'body'      => 'nullable|string',
            'status'    => 'required|in:active,inactive',
        ]);

        $validated['body'] = $this->cleanBody($validated['body'] ?? '');

        $lanjutDraft = $request->input('template_id') === '0';          

        if ($lanjutDraft && !session()->has('draft_template')) { 
            return redirect()->route('template.index')
                ->with('error', 'Sesi draft habis. Silakan buat ulang template.');
        }

        $draftLama = $lanjutDraft ? session('draft_template', []) : [];  

        session(['draft_template' => array_merge($draftLama, $validated)]);

        return redirect()->route('template.editor.draft');
    }

    // UPDATE DATA (KALAU DARI TOMBOL EDIT / DB)
        public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:255',
            'subject'   => 'nullable|string|max:255',
            'body'      => 'nullable|string',
            'status'    => 'required|in:active,inactive',
        ]);

        $validated['body'] = $this->cleanBody($validated['body'] ?? '');

        if ($id == 0) {
            session(['draft_template' => array_merge(session('draft_template', []), $validated)]);

            return redirect()->route('template.editor.draft')
                ->with('success', 'Data diperbarui. Silakan lanjutkan desain Header & Footer.');
        }

        // Kalau ID beneran (data lama dari DB)
        $template = TemplateEmail::findOrFail($id);
        $template->update($validated);

        return redirect()->route('template.editor', $template->template_email_id)
            ->with('success', 'Data dasar diperbarui. Silakan lanjutkan desain Header & Footer.');
    }

    public function destroy($id)
    {
        $template = TemplateEmail::findOrFail($id);
        $template->delete();
        return redirect()->route('template.index')->with('success', 'Template berhasil dihapus.');
    }

    // HALAMAN EDITOR UNTUK DATA BARU
    public function editorDraft()
    {
        $draft = session('draft_template');
        
        if (!$draft) {
            return redirect()->route('template.index')->with('error', 'Sesi habis. Silakan isi ulang form.');
        }

        $template = new TemplateEmail();
        $template->template_email_id = 0;
        $template->nama      = $draft['nama'];
        $template->deskripsi = $draft['deskripsi'] ?? ''; 
        $template->subject   = $draft['subject'] ?? '';   
        $template->body      = $draft['body'];
        $template->status    = $draft['status'] ?? 'active';

        $template->header_html = $draft['header_html'] ?? '';
        $template->footer_html = $draft['footer_html'] ?? '';
        $template->header_css  = $draft['header_css'] ?? '';
        $template->footer_css  = $draft['footer_css'] ?? '';
        
        return view('template.editor', compact('template'));
    }

    // HALAMAN EDITOR UNTUK DATA LAMA 
    public function editor($id)
    {
        $template = TemplateEmail::findOrFail($id);
        return view('template.editor', compact('template'));
    }

    // SIMPAN FINAL (GABUNGKAN SESSION + DESAIN GRAPESJS)
    public function saveEditor(Request $request, $id)
    {
        $validated = $request->validate([
            'header_html' => 'nullable|string|max:200000',
            'footer_html' => 'nullable|string|max:200000',
            'header_css'  => 'nullable|string|max:200000',
            'footer_css'  => 'nullable|string|max:200000',
        ]);

        $css = ($validated['header_css'] ?? '') . "\n" . ($validated['footer_css'] ?? '');

        $desain = [
            'header_html' => $this->inlineFragment($validated['header_html'] ?? '', $css),
            'footer_html' => $this->inlineFragment($validated['footer_html'] ?? '', $css),
            'header_css'  => '',
            'footer_css'  => '',
        ];

        // TEMPLATE BARU (belum ada di DB)
        if ((int) $id === 0) {
            $draft = session('draft_template');

            if (!$draft) {
                return $request->ajax()
                    ? response()->json(['success' => false, 'message' => 'Sesi habis. Isi ulang form template.'], 410)
                    : redirect()->route('template.index')->with('error', 'Sesi habis. Silakan isi ulang form.');
            }

            $draft = array_merge($draft, $desain);

            if ($request->ajax()) {
                session(['draft_template' => $draft]);        
                return response()->json(['success' => true]);
            }

            TemplateEmail::create($draft);                    
            session()->forget('draft_template');

            return redirect()->route('template.index')
                ->with('success', 'Template baru berhasil dibuat dan disimpan!');
        }

        // TEMPLATE LAMA (sudah ada di DB) 
        TemplateEmail::findOrFail($id)->update($desain);

        if ($request->ajax()) {
            return response()->json(['success' => true]);    
        }

        return redirect()->route('template.index')
            ->with('success', 'Desain Header & Footer berhasil disimpan.');
    }

    // Inline CSS ke dalam HTML potongan header/footer
    private function inlineFragment(string $html, string $css): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);

        $out = (new CssToInlineStyles())->convert($html, $css);

        return preg_match('#<body[^>]*>(.*)</body>#is', $out, $m) ? trim($m[1]) : $html;
    }

    // Bersihkan body
    private function cleanBody(?string $body): ?string
    {
        if (empty($body)) return $body;
        $body = preg_replace('/<p[^>]*>(\s|&nbsp;)*<\/p>/i', '', $body);
        $body = preg_replace('/<div[^>]*>(\s|&nbsp;)*<\/div>/i', '', $body);
        $body = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br>', $body);
        $body = preg_replace('/(&nbsp;){2,}/i', ' ', $body);
        return trim($body);
    }
}