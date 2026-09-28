<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Email::with(['emailLog.penerima', 'template'])
            ->withCount([
                'emailLog as total_penerima',
                'emailLog as total_berhasil' => fn($q) => $q->where('status', 'success'),
                'emailLog as total_gagal'    => fn($q) => $q->where('status', 'failed'),
            ]);

        // Filter search
        if ($request->filled('search')) {
            $query->where('subject', 'like', "%{$request->search}%");
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayat = $query->latest('sent_at')->paginate(20)->withQueryString();

        return view('riwayat', compact('riwayat'));
    }
}