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
    /**
     * Halaman Buat Email (multi-step)
     */
    public function index(Request $request)
    {
        $template = TemplateEmail::aktif()->orderBy('id')->get();
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

    /**
     * Preview email sebelum kirim
     */
    public function preview(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,id',
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'    => 'nullable|array',
            'grup_ids'      => 'nullable|array',
            'penerima_ids'  => 'nullable|array',
        ]);

        session(['email_draft' => $data]);

        $penerima = $this->resolvePenerima(
            $data['penerima_mode'],
            $data['divisi_ids'] ?? [],
            $data['grup_ids'] ?? [],
            $data['penerima_ids'] ?? []
        );

        $divisiDipilih = !empty($data['divisi_ids'])
            ? Divisi::whereIn('id', $data['divisi_ids'])->pluck('nama')->toArray()
            : [];

        $grupDipilih = !empty($data['grup_ids'])
            ? Grup::whereIn('id', $data['grup_ids'])->pluck('nama')->toArray()
            : [];

        return view('preview-email', compact('data', 'penerima', 'divisiDipilih', 'grupDipilih'));
    }

    /**
     * Kirim email via SMTP
     */
    public function kirim(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,id',
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'    => 'nullable|array',
            'grup_ids'      => 'nullable|array',
            'penerima_ids'  => 'nullable|array',
        ]);

        // Ambil penerima sesuai mode
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

        // 1. Simpan email ke DB
        $emailRecord = Email::create([
            'template_id' => $data['template_id'] ?: null,
            'nama'        => $data['nama'],
            'subject'     => $data['subject'],
            'body'        => $data['body'],
            'status'      => 'sent',
            'sent_at'     => now(),
        ]);

        // 2. Simpan relasi email ↔ penerima (email_penerima)
        $emailRecord->penerima()->sync($penerima->pluck('id')->toArray());

        // 3. Kirim email satu per satu + catat log
        $totalBerhasil = 0;
        $totalGagal    = 0;

        foreach ($penerima as $p) {
            try {
                Mail::to($p->email)
                    ->send(new BroadcastMail(
                        $data['nama'],
                        $data['subject'],
                        $data['body']
                    ));

                EmailLog::create([
                    'email_id'       => $emailRecord->id,
                    'penerima_id'    => $p->id,
                    'penerima_email' => $p->email,
                    'status'         => 'success',
                    'sent_at'        => now(),
                ]);

                $totalBerhasil++;

            } catch (\Throwable $e) {
                EmailLog::create([
                    'email_id'       => $emailRecord->id,
                    'penerima_id'    => $p->id,
                    'penerima_email' => $p->email,
                    'status'         => 'failed',
                    'error_message'  => $e->getMessage(),
                    'sent_at'        => now(),
                ]);

                $totalGagal++;
            }
        }

        // 4. Update status email (kalau semua gagal)
        if ($totalBerhasil === 0 && $totalGagal > 0) {
            $emailRecord->update(['status' => 'failed']);
        }

        // 5. Hapus draft dari session
        session()->forget('email_draft');

        // 6. Redirect dengan notifikasi
        return redirect()->route('riwayat')
            ->with('success', "Pengiriman selesai: {$totalBerhasil} berhasil, {$totalGagal} gagal.");
    }

    /**
     * Helper: resolve penerima sesuai mode
     */
    private function resolvePenerima($mode, $divisiIds, $grupIds, $penerimaIds)
    {
        $query = Penerima::aktif()->with('divisi');

        if ($mode === 'divisi' && !empty($divisiIds)) {
            $query->whereIn('divisi_id', $divisiIds);
        } elseif ($mode === 'grup' && !empty($grupIds)) {
            $query->whereHas('grup', fn($q) => $q->whereIn('grup.id', $grupIds));
        } elseif ($mode === 'manual' && !empty($penerimaIds)) {
            $query->whereIn('id', $penerimaIds);
        }

        return $query->distinct()->orderBy('nama')->get();
    }
}