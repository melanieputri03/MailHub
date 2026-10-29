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
use Illuminate\Support\Facades\Validator;

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

        //  Decode body di draft kalau ternyata JSON
        if ($draft && isset($draft['body']) && is_string($draft['body'])) {
            $trimmed = trim($draft['body']);
            if (str_starts_with($trimmed, '{"')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded) && isset($decoded['body'])) {
                    $draft['body'] = $decoded['body'];
                }
            }
        }

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

        public function resetDraft()
    {
        session()->forget('email_draft');
        return redirect()->route('email.index');
    }

    // Preview email sebelum kirim
    public function preview(Request $request)
    {
        $data = $request->validate([
            'nama_email'    => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,template_email_id',
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'           => 'nullable|array',
            'divisi_ids.*'         => 'nullable|integer|distinct|exists:divisi,divisi_id',
            'grup_ids'             => 'nullable|array',
            'grup_ids.*'           => 'nullable|integer|distinct|exists:grup,grup_id',
            'penerima_ids'         => 'nullable|array',
            'penerima_ids.*'       => 'nullable|integer|distinct|exists:penerima,penerima_id',
            'attachments'          => 'nullable|array',
            'attachments.*'        => 'file|max:3072|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,zip',
            'existing_attachments'              => 'nullable|array',
            'existing_attachments.*.filename'   => 'nullable|string',
            'existing_attachments.*.original'   => 'nullable|string',
            'existing_attachments.*.size'       => 'nullable|integer',
        ]);

        //  Decode body kalau ternyata JSON string
        if (isset($data['body']) && is_string($data['body'])) {
            $trimmed = trim($data['body']);
            if (str_starts_with($trimmed, '{"')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded) && isset($decoded['body'])) {
                    $data['body'] = $decoded['body'];
                }
            }
        }

        $attachmentMap = [];

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

        $totalSize = array_sum(array_column($attachmentPaths, 'size'));
        if ($totalSize > 10 * 1024 * 1024) {
            $this->cleanupNewUploads($request, $sessionDraft);

            return back()->with('error', 'Total lampiran melebihi 10 MB. Silakan kompres file dulu.');
        }

        $data['attachments'] = $attachmentPaths;
        session(['email_draft' => $data]);

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

        $tpl = !empty($data['template_id']) ? TemplateEmail::find($data['template_id']) : null;
        return view('preview-email', compact('data', 'penerima', 'divisiDipilih', 'grupDipilih', 'tpl'));

    }

    // Kirim email via SMTP (dengan attachment)
    public function kirim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_email'    => 'required|string', 
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,template_email_id',
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'           => 'nullable|array',
            'divisi_ids.*'         => 'nullable|integer|distinct|exists:divisi,divisi_id',
            'grup_ids'             => 'nullable|array',
            'grup_ids.*'           => 'nullable|integer|distinct|exists:grup,grup_id',
            'penerima_ids'         => 'nullable|array',
            'penerima_ids.*'       => 'nullable|integer|distinct|exists:penerima,penerima_id',
            'attachments'          => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->route('email.index')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        $bodyHtml = $data['body'];

        if (is_string($bodyHtml) && str_starts_with(trim($bodyHtml), '{"')) {
            $decoded = json_decode($bodyHtml, true);
            if (is_array($decoded) && isset($decoded['body'])) {
                $bodyHtml = $decoded['body'];
            }
        }

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

        // Guard bandwidth
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
            'nama'        => $data['nama_email'],
            'subject'     => $data['subject'],
            'body'        => $bodyHtml,
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
                    $data['nama_email'],
                    $data['subject'],
                    $bodyHtml,
                    $data['template_id'] ?? null
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

        session()->forget('email_draft');

        return redirect()->route('riwayat')
            ->with('success', "Pengiriman selesai: {$totalBerhasil} berhasil, {$totalGagal} gagal.");
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $file = $request->file('file');

        $originalName = $file->getClientOriginalName();
        $safeName     = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $filename     = time() . '_' . uniqid() . '_' . $safeName;

        $uploadPath = public_path('uploads/email-images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $size = $file->getSize();
        $file->move($uploadPath, $filename);

        return response()->json([
            'location' => url('uploads/email-images/' . $filename),
        ]);
    }

        private function resolvePenerima($mode, $divisiIds, $grupIds, $penerimaIds)
    {
        $query = Penerima::aktif()->with('divisi');

        switch ($mode) {
            case 'semua':
                break;
            case 'divisi':
                if (empty($divisiIds)) return collect(); 
                $query->whereIn('divisi_id', $divisiIds);
                break;
            case 'grup':
                if (empty($grupIds)) return collect(); 
                $query->whereHas('grup', fn ($q) => $q->whereIn('grup.grup_id', $grupIds));
                break;
            case 'manual':
                if (empty($penerimaIds)) return collect(); 
                $query->whereIn('penerima_id', $penerimaIds);
                break;
            default:
                return collect();
        }

        return $query->distinct()->orderBy('nama')->get();
    }

    private function cleanupNewUploads(Request $request, array $sessionDraft = [])
    {
        if (!$request->hasFile('attachments')) {
            return;
        }

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

            $pattern = $uploadPath . '/*_' . $safeName;
            foreach (glob($pattern) as $existingFile) {
                $basename = basename($existingFile);
                if (in_array($basename, $oldFilenames, true)) {
                    continue;
                }
                if (filemtime($existingFile) > time() - 60) {
                    @unlink($existingFile);
                }
            }
        }
    }
}