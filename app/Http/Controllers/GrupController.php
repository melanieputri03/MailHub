<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use App\Models\Penerima;
use Illuminate\Http\Request;

class GrupController extends Controller
{
    public function index()
    {
        $grup     = Grup::withCount('penerima')->with('penerima')->latest()->get();
        $penerima = Penerima::aktif()->with('divisi')->orderBy('nama')->get();

        return view('grup', compact('grup', 'penerima'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'penerima_ids'   => 'nullable|array',
            'penerima_ids.*' => 'exists:penerima,penerima_id',         // ← UBAH
        ]);

        $grup = Grup::create([
            'nama'      => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        if (!empty($validated['penerima_ids'])) {
            $grup->penerima()->sync($validated['penerima_ids']);
        }

        return redirect()->route('grup.index')
            ->with('success', 'Grup berhasil dibuat.');
    }

    public function update(Request $request, Grup $grup)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'deskripsi'      => 'nullable|string',
            'penerima_ids'   => 'nullable|array',
            'penerima_ids.*' => 'exists:penerima,penerima_id',         // ← UBAH
        ]);

        $grup->update([
            'nama'      => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        if (isset($validated['penerima_ids'])) {
            $grup->penerima()->sync($validated['penerima_ids']);
        }

        return redirect()->route('grup.index')
            ->with('success', 'Grup berhasil diperbarui.');
    }

    public function destroy(Grup $grup)
    {
        $grup->delete();

        return redirect()->route('grup.index')
            ->with('success', 'Grup berhasil dihapus.');
    }
}