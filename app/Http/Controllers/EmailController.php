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
    public function index(Request $request)
    {
        $template = TemplateEmail::aktif()->orderBy('template_email_id')->get();  // ← UBAH
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

    public function preview(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,template_email_id',   // ← UBAH
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
            ? Divisi::whereIn('divisi_id', $data['divisi_ids'])->pluck('nama')->toArray()   // ← UBAH
            : [];

        $grupDipilih = !empty($data['grup_ids'])
            ? Grup::whereIn('grup_id', $data['grup_ids'])->pluck('nama')->toArray()         // ← UBAH
            : [];

        return view('preview-email', compact('data', 'penerima', 'divisiDipilih', 'grupDipilih'));
    }

    public function kirim(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'template_id'   => 'nullable|exists:template_email,template_email_id',   // ← UBAH
            'penerima_mode' => 'required|in:semua,divisi,grup,manual',
            'divisi_ids'    => 'nullable|array',
            'grup_ids'      => 'nullable|array',
            'penerima_ids'  => 'nullable|array',
        ]);

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

        // Simpan email
        $emailRecord = Email::create([
            'template_id' => $data['template_id'] ?: null,
            'nama'        => $data['nama'],
            'subject'     => $data['subject'],
            'body'        => $data['body'],
            'status'      => 'sent',
            'sent_at'     => now(),
        ]);

        // Simpan relasi email ↔ penerima
        $emailRecord->penerima()->sync($penerima->pluck('penerima_id')->toArray());   // ← UBAH

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
                    'email_id'       => $emailRecord->email_id,       // ← UBAH
                    'penerima_id'    => $p->penerima_id,              // ← UBAH
                    'penerima_email' => $p->email,
                    'status'         => 'success',
                    'sent_at'        => now(),
                ]);

                $totalBerhasil++;
            } catch (\Throwable $e) {
                EmailLog::create([
                    'email_id'       => $emailRecord->email_id,       // ← UBAH
                    'penerima_id'    => $p->penerima_id,              // ← UBAH
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

    private function resolvePenerima($mode, $divisiIds, $grupIds, $penerimaIds)
    {
        $query = Penerima::aktif()->with('divisi');

        if ($mode === 'divisi' && !empty($divisiIds)) {
            $query->whereIn('divisi_id', $divisiIds);
        } elseif ($mode === 'grup' && !empty($grupIds)) {
            $query->whereHas('grup', fn($q) => $q->whereIn('grup.grup_id', $grupIds));   // ← UBAH
        } elseif ($mode === 'manual' && !empty($penerimaIds)) {
            $query->whereIn('penerima_id', $penerimaIds);                                // ← UBAH
        }

        return $query->distinct()->orderBy('nama')->get();
    }
}