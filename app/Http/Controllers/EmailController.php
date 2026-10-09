<?php

namespace App\Http\Controllers;

use App\Mail\BroadcastMail;
use App\Models\Divisi;
use App\Models\Email;
use App\Models\EmailLog;
use App\Models\Grup;
use App\Models\Penerima;
use App\Models\TemplateEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    // Halaman Buat Email (multi-step)
    public function index(Request $request)
    {
        $template = TemplateEmail::aktif()->orderBy('template_email_id')->get();
        $divisi   = Divisi::where('status', 'active')->orderBy('nama')->get();
        $grup     = Grup::withCount('penerima')->latest()->get();
        $penerima = Penerima::aktif()->with('divisi')->orderBy('nama')->get();

        $draft = session('email_draft', null);

        $selectedTemplate = null;
        if ($request->filled('template')) {
            $selectedTemplate = TemplateEmail::find($request->template);
        }

        return view('email', compact(
            'template',
            'divisi',
            'grup',
            'penerima',
            'selectedTemplate',
            'draft',
        ));
    }

    // Preview email sebelum kirim
    public function preview(Request $request)
    {
        $data = $request->validate([
            'nama'                 => 'required|string',
            'subject'              => 'required|string',
            'body'                 => 'required|string',
            'template_id'          => 'nullable|exists:template_email,template_email_id',
            'penerima_mode'        => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'           => 'nullable|array',
            'grup_ids'             => 'nullable|array',
            'penerima_ids'         => 'nullable|array',
            'attachments'          => 'nullable|array',
            'attachments.*'        => 'file|max:3072|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip',
            'existing_attachments'              => 'nullable|array',
            'existing_attachments.*.filename'   => 'nullable|string',
            'existing_attachments.*.original'   => 'nullable|string',
            'existing_attachments.*.size'       => 'nullable|integer',
        ]);

        $attachmentMap = [];

        // 1. Ambil dari session dulu (file yang tersimpan sebelumnya)
        $sessionDraft = session('email_draft', []);
        if (!empty($sessionDraft['attachments'])) {
            foreach ($sessionDraft['attachments'] as $att) {
                if (!empty($att['filename'])
                    && file_exists(public_path('uploads/email-attachments/' . $att['filename']))) {
                    $attachmentMap[$att['filename']] = [
                        'filename' => $att['filename'],
                        'original' => $att['original'],
                        'size'     => (int) $att['size'],
                    ];
                }
            }
        }

        // 2. Override/tambah dari existing_attachments (hidden input form)
        if ($request->filled('existing_attachments')) {
            foreach ($request->input('existing_attachments') as $att) {
                if (!empty($att['filename'])
                    && file_exists(public_path('uploads/email-attachments/' . $att['filename']))) {
                    $attachmentMap[$att['filename']] = [
                        'filename' => $att['filename'],
                        'original' => $att['original'] ?? $att['filename'],
                        'size'     => (int) ($att['size'] ?? 0),
                    ];
                }
            }
        }

        // 3. Tambah file baru yang diupload
        if ($request->hasFile('attachments')) {
            $uploadPath = public_path('uploads/email-attachments');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('attachments') as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }

                $originalName = $file->getClientOriginalName();
                $safeName     = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $filename     = time() . '_' . uniqid() . '_' . $safeName;

                $size = $file->getSize();

                $file->move($uploadPath, $filename);

                $attachmentMap[$filename] = [
                    'filename' => $filename,
                    'original' => $originalName,
                    'size'     => $size,
                ];
            }
        }

        // Reset index jadi array numerik
        $attachmentPaths = array_values($attachmentMap);

        // Cek total size max 10 MB
        $totalSize = array_sum(array_column($attachmentPaths, 'size'));
        if ($totalSize > 10 * 1024 * 1024) {
            $this->cleanupNewUploads($request, $sessionDraft);

            return back()->with('error', 'Total lampiran melebihi 10 MB. Silakan kompres file dulu.');
        }

        // Simpan ke session
        $data['attachments'] = $attachmentPaths;
        session(['email_draft' => $data]);

        // Resolve penerima
        $penerima = $this->resolvePenerima(
            $data['penerima_mode'],
            $data['divisi_ids'] ?? [],
            $data['grup_ids'] ?? [],
            $data['penerima_ids'] ?? []
        );

        $divisiDipilih = !empty($data['divisi_ids'])
            ? Divisi::whereIn('divisi_id', $data['divisi_ids'])->pluck('nama')->toArray()
            : [];

        $grupDipilih = !empty($data['grup_ids'])
            ? Grup::whereIn('grup_id', $data['grup_ids'])->pluck('nama')->toArray()
            : [];
        return view('preview-email', compact('data', 'penerima', 'divisiDipilih', 'grupDipilih'));
    }

    // Kirim email via SMTP (dengan attachment)
    public function kirim(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,template_email_id',
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'    => 'nullable|array',
            'grup_ids'      => 'nullable|array',
            'penerima_ids'  => 'nullable|array',
            'attachments'   => 'nullable|array',
        ]);

        // AMBIL ATTACHMENT
        $sessionAttachments = session('email_draft.attachments', []);
        $requestAttachments = $data['attachments'] ?? [];

        $attachmentMap = [];
        foreach ($sessionAttachments as $att) {
            if (!empty($att['filename'])) {
                $attachmentMap[$att['filename']] = $att;
            }
        }
        foreach ($requestAttachments as $att) {
            if (!empty($att['filename'])) {
                $attachmentMap[$att['filename']] = $att;
            }
        }
        $attachments = array_values($attachmentMap);

        // Resolve penerima
        $penerima = $this->resolvePenerima(
            $data['penerima_mode'],
            $data['divisi_ids'] ?? [],
            $data['grup_ids'] ?? [],
            $data['penerima_ids'] ?? []
        );

        if ($penerima->isEmpty()) {
            return redirect()->route('email.index')
                ->with('error', 'Tidak ada penerima yang valid.');
        }

        // Guard bandwidth — cegah limit Gmail (1.5 GB/hari)
        if (!empty($attachments)) {
            $totalSize = 0;
            foreach ($attachments as $att) {
                $totalSize += $att['size'] ?? 0;
            }
            $totalBandwidth = $totalSize * $penerima->count();

            if ($totalBandwidth > 1.4 * 1024 * 1024 * 1024) {
                return redirect()->route('email.index')
                    ->with('error', 'Total bandwidth pengiriman melebihi batas Gmail. Kurangi lampiran atau jumlah penerima.');
            }
        }

        // 1. Simpan email ke DB
        $emailRecord = Email::create([
            'template_id' => $data['template_id'] ?: null,
            'nama'        => $data['nama'],
            'subject'     => $data['subject'],
            'body'        => $data['body'],
            'status'      => 'sent',
            'sent_at'     => now(),
        ]);

        // 2. Simpan relasi email ↔ penerima
        $emailRecord->penerima()->sync($penerima->pluck('penerima_id')->toArray());

        // 3. Kirim email + attachment
        $totalBerhasil = 0;
        $totalGagal    = 0;

        foreach ($penerima as $p) {
            try {
                $mailable = new BroadcastMail(
                    $data['nama'],
                    $data['subject'],
                    $data['body']
                );

                // Lampirkan file
                foreach ($attachments as $att) {
                    $filePath = public_path('uploads/email-attachments/' . $att['filename']);
                    if (file_exists($filePath)) {
                        $mailable->attach($filePath, [
                            'as' => $att['original'] ?? $att['filename'],
                        ]);
                    }
                }

                Mail::to($p->email)->send($mailable);

                EmailLog::create([
                    'email_id'       => $emailRecord->email_id,
                    'penerima_id'    => $p->penerima_id,
                    'penerima_email' => $p->email,
                    'status'         => 'success',
                    'sent_at'        => now(),
                ]);

                $totalBerhasil++;
            } catch (\Throwable $e) {
                EmailLog::create([
                    'email_id'       => $emailRecord->email_id,
                    'penerima_id'    => $p->penerima_id,
                    'penerima_email' => $p->email,
                    'status'         => 'failed',
                    'error_message'  => $e->getMessage(),
                    'sent_at'        => now(),
                ]);

                $totalGagal++;
            }
        }

        if ($totalBerhasil === 0 && $totalGagal > 0) {
            $emailRecord->update(['status' => 'failed']);
        }

        // Bersihkan session draft
        session()->forget('email_draft');

        return redirect()->route('riwayat')
            ->with('success', "Pengiriman selesai: {$totalBerhasil} berhasil, {$totalGagal} gagal.");
    }

    // Helper: resolve penerima sesuai mode
    private function resolvePenerima($mode, $divisiIds, $grupIds, $penerimaIds)
    {
        $query = Penerima::aktif()->with('divisi');

        if ($mode === 'divisi' && !empty($divisiIds)) {
            $query->whereIn('divisi_id', $divisiIds);
        } elseif ($mode === 'grup' && !empty($grupIds)) {
            $query->whereHas('grup', fn($q) => $q->whereIn('grup.grup_id', $grupIds));
        } elseif ($mode === 'manual' && !empty($penerimaIds)) {
            $query->whereIn('penerima_id', $penerimaIds);
        }

        return $query->distinct()->orderBy('nama')->get();
    }

    // Helper: hapus file baru yang barusan diupload kalau validasi gagal.
    private function cleanupNewUploads(Request $request, array $sessionDraft = [])
    {
        if (!$request->hasFile('attachments')) {
            return;
        }

        // Kumpulkan filename lama dari session, biar gak kehapus
        $oldFilenames = collect($sessionDraft['attachments'] ?? [])
            ->pluck('filename')
            ->filter()
            ->all();

        $uploadPath = public_path('uploads/email-attachments');

        foreach ($request->file('attachments') as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $safeName     = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);

            // Cari file yang barusan diupload (pola: time_uniqid_safeName)
            $pattern = $uploadPath . '/*_' . $safeName;
            foreach (glob($pattern) as $existingFile) {
                $basename = basename($existingFile);
                // Skip kalau ini file lama dari session
                if (in_array($basename, $oldFilenames, true)) {
                    continue;
                }
                // Hapus kalau umurnya < 60 detik (baru aja diupload)
                if (filemtime($existingFile) > time() - 60) {
                    @unlink($existingFile);
                }
            }
        }
    }
}