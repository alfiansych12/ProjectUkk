<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $allowedPerPage = [5, 10, 15, 20];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $kategoris = Kategori::withCount('alats')
            ->withSum('alats', 'stok')
            ->latest()
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);
            
        return view('admin.kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori',
        ]);

        Kategori::create($request->all());

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|unique:kategoris,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($request->all());

        return back()->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
