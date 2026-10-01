<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Email;
use App\Models\EmailLog;
use App\Models\Grup;
use App\Models\Penerima;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        //  STATISTIK UTAMA 
        $totalPenerima = Penerima::aktif()->count();
        $totalGrup     = Grup::count();
        $totalEmail    = Email::count();
        $totalDivisi   = Divisi::where('status', 'active')->count();

        // Berhasil & gagal (total dari semua log)
        $totalBerhasil = EmailLog::where('status', 'success')->count();
        $totalGagal    = EmailLog::where('status', 'failed')->count();

        // Penerima baru bulan ini
        $penerimaBulanIni = Penerima::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Email terkirim bulan ini
        $emailBulanIni = Email::whereMonth('sent_at', now()->month)
            ->whereYear('sent_at', now()->year)
            ->count();

        //  AKTIVITAS TERBARU =
        $aktivitasTerbaru = Email::withCount([
                'emailLog as total_penerima',
                'emailLog as total_berhasil' => fn($q) => $q->where('status', 'success'),
                'emailLog as total_gagal'    => fn($q) => $q->where('status', 'failed'),
            ])
            ->latest('sent_at')
            ->take(5)
            ->get();

        //  STATISTIK 7 HARI 
        $statistik7Hari = Email::select(
                DB::raw('DATE(sent_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->whereNotNull('sent_at')
            ->where('sent_at', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('dashboard', compact(
            'totalPenerima',
            'totalGrup',
            'totalEmail',
            'totalDivisi',
            'totalBerhasil',
            'totalGagal',
            'penerimaBulanIni',
            'emailBulanIni',
            'aktivitasTerbaru',
            'statistik7Hari',
        ));
    }
}