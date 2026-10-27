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

        // Simpan sementara ke Session
        session(['draft_template' => $validated]);

        // Arahkan ke Editor (mode Draft)
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
            session(['draft_template' => $validated]);
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

        // Bikin objek template palsu
        $template = new TemplateEmail();
        $template->template_email_id = 0; // ID 0 artinya belum masuk DB
        $template->nama = $draft['nama'];
        $template->body = $draft['body'];
        
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

        // CSS di-inline ke atribut style, jadi kolom *_css dikosongkan
        $desain = [
            'header_html' => $this->inlineFragment($validated['header_html'] ?? '', $css),
            'footer_html' => $this->inlineFragment($validated['footer_html'] ?? '', $css),
            'header_css'  => '',
            'footer_css'  => '',
        ];

        $draft = session('draft_template');

        if ($draft) {
            // DATA BARU (DARI SESSION)
            TemplateEmail::create(array_merge($draft, $desain));
            session()->forget('draft_template');

            return redirect()->route('template.index')
                ->with('success', 'Template baru berhasil dibuat dan disimpan!');
        }

        // EDIT DATA LAMA (DARI DB)
        TemplateEmail::findOrFail($id)->update($desain);

        return redirect()->route('template.index')
            ->with('success', 'Desain Header & Footer berhasil disimpan.');
    }

    // Inline CSS ke dalam HTML potongan header/footer
    private function inlineFragment(string $html, string $css): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';   // kosong → broadcast.blade.php pakai header/footer bawaan
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