<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori');
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alats = $query->latest()->paginate(12);
        $kategoris = Kategori::all();

        if (Auth::user()->role === 'admin') {
            return view('admin.alat', compact('alats', 'kategoris'));
        }

        return view('peminjam.alat', compact('alats', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required',
            'stok' => 'required|integer',
            'harga_sewa_per_hari' => 'required|numeric',
            'denda_per_hari' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        $data = $request->except('images');
        $imagePaths = [];

        if ($request->hasFile('images')) {
            $uploadDir = public_path('uploads/alats');
            if (!file_exists($uploadDir))
                mkdir($uploadDir, 0755, true);

            foreach ($request->file('images') as $image) {
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadDir, $filename);
                $imagePaths[] = 'uploads/alats/' . $filename;
            }
        }

        $data['images'] = $imagePaths;
        Alat::create($data);

        return back()->with('success', 'Alat berhasil ditambahkan');
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'nama_alat' => 'required',
            'stok' => 'required|integer',
            'harga_sewa_per_hari' => 'required|numeric',
            'denda_per_hari' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        $data = $request->except(['images', 'existing_images']);

        // 1. Ambil foto lama yang tersisa (yang tidak dihapus di UI)
        $existingImages = [];
        if ($request->has('existing_images')) {
            $existingImages = json_decode($request->existing_images, true) ?? [];
            // Sanitasi: pastikan hanya path relatif yang disimpan
            $baseUrl = asset('');
            $existingImages = array_map(function ($path) use ($baseUrl) {
                return str_replace($baseUrl, '', $path);
            }, $existingImages);
        }

        // Hapus file fisik jika foto dihapus dari daftar existing
        if ($alat->images) {
            foreach ($alat->images as $oldPath) {
                if (!in_array($oldPath, $existingImages)) {
                    $fullOldPath = public_path($oldPath);
                    if (file_exists($fullOldPath)) {
                        unlink($fullOldPath);
                    }
                }
            }
        }

        $imagePaths = $existingImages;

        // 2. Tambahkan foto baru jika ada
        if ($request->hasFile('images')) {
            $uploadDir = public_path('uploads/alats');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($request->file('images') as $image) {
                $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadDir, $filename);
                $imagePaths[] = 'uploads/alats/' . $filename;
            }
        }

        $data['images'] = $imagePaths;
        $alat->update($data);

        return back()->with('success', 'Alat berhasil diperbarui');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->images) {
            foreach ($alat->images as $path) {
                $fullPath = public_path($path);
                if (file_exists($fullPath))
                    unlink($fullPath);
            }
        }
        $alat->delete();
        return back()->with('success', 'Alat berhasil dihapus');
    }
}
