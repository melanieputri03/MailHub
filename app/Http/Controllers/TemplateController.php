<?php

namespace App\Http\Controllers;

use App\Models\TemplateEmail;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $template = TemplateEmail::orderBy('template_email_id', 'desc')->get();
        return view('template.index', compact('template'));
    }

    // ⚡ SIMPAN DATA DASAR KE SESSION (BELUM KE DB) ⚡
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

    // ⚡ UPDATE DATA (KALAU DARI TOMBOL EDIT / DB) ⚡
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

        // ⚡ KALAU ID = 0 (DATA BARU), SIMPAN KE SESSION LAGI ⚡
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

    // ⚡ HALAMAN EDITOR UNTUK DATA BARU (DARI SESSION) ⚡
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

    // ⚡ HALAMAN EDITOR UNTUK DATA LAMA (DARI DB) ⚡
    public function editor($id)
    {
        $template = TemplateEmail::findOrFail($id);
        return view('template.editor', compact('template'));
    }

    // ⚡ SIMPAN FINAL (GABUNGKAN SESSION + DESAIN GRAPESJS) ⚡
    public function saveEditor(Request $request, $id)
    {
        $validated = $request->validate([
            'header_html' => 'nullable|string',
            'footer_html' => 'nullable|string',
            'header_css'  => 'nullable|string',
            'footer_css'  => 'nullable|string',
        ]);

        $draft = session('draft_template');

        if ($draft) {
            // === JIKA DATA BARU (DARI SESSION) ===
            $draft['header_html'] = $validated['header_html'] ?? '';
            $draft['footer_html'] = $validated['footer_html'] ?? '';
            $draft['header_css']  = $validated['header_css'] ?? '';
            $draft['footer_css']  = $validated['footer_css'] ?? '';

            TemplateEmail::create($draft);

            // Hapus session karena udah masuk DB
            session()->forget('draft_template');

            return redirect()->route('template.index')->with('success', 'Template baru berhasil dibuat dan disimpan!');
        } else {
            // === JIKA EDIT DATA LAMA (DARI DB) ===
            $template = TemplateEmail::findOrFail($id);
            $template->update([
                'header_html' => $validated['header_html'] ?? '',
                'footer_html' => $validated['footer_html'] ?? '',
                'header_css'  => $validated['header_css'] ?? '',
                'footer_css'  => $validated['footer_css'] ?? '',
            ]);

            return redirect()->route('template.index')->with('success', 'Desain Header & Footer berhasil disimpan.');
        }
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