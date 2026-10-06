<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Penerima;
use Illuminate\Http\Request;

class PenerimaController extends Controller
{
    public function index(Request $request)
    {
        $query = Penerima::with('divisi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        $penerima = $query->latest()->paginate(15)->withQueryString();
        $divisi   = Divisi::orderBy('nama')->get();

        return view('penerima', compact('penerima', 'divisi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:penerima,email',
            'divisi_id' => 'required|exists:divisi,divisi_id',
            'jabatan'   => 'nullable|string|max:150',
            'status'    => 'required|in:active,inactive',
        ]);

        Penerima::create($validated);

        return redirect()->route('penerima.index')
            ->with('success', 'Penerima berhasil ditambahkan.');
    }

    public function update(Request $request, Penerima $penerima)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:penerima,email,' . $penerima->penerima_id . ',penerima_id',  // ← TAMBAH ,penerima_id
            'divisi_id' => 'required|exists:divisi,divisi_id',
            'jabatan'   => 'nullable|string|max:150',
            'status'    => 'required|in:active,inactive',
        ]);

        $penerima->update($validated);

        return redirect()->route('penerima.index')
            ->with('success', 'Penerima berhasil diperbarui.');
    }

    public function destroy(Penerima $penerima)
    {
        $penerima->delete();

        return redirect()->route('penerima.index')
            ->with('success', 'Penerima berhasil dihapus.');
    }
}