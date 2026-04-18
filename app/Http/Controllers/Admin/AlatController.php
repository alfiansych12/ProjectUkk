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
        $perPage = $request->query('per_page', 5);
        $allowedPerPage = [5, 10, 15, 20];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $query = Alat::with('kategori');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alats = $query->latest()->paginate($perPage)->appends(['per_page' => $perPage] + $request->query());
        $kategoris = Kategori::all();

        if (Auth::user()->role === 'admin') {
            return view('admin.alat', compact('alats', 'kategoris'));
        }

        return view('peminjam.alat', compact('alats', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_alat' => 'required',
            'stok' => 'required|integer',
            'harga_sewa_per_hari' => 'required|numeric',
            'denda_per_hari' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except('images');
        $imagePaths = [];

        if ($request->hasFile('images')) {
            $uploadDir = public_path('uploads/alats');
            if (!file_exists($uploadDir))
                mkdir($uploadDir, 0755, true);

            $images = $request->file('images');
            // Ensure it's an array even if single file
            if (!is_array($images) && $images) {
                $images = [$images];
            }
            
            if (is_array($images)) {
                foreach ($images as $image) {
                    if ($image && $image->isValid()) {
                        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadDir, $filename);
                        $imagePaths[] = 'uploads/alats/' . $filename;
                    }
                }
            }
        }

        $data['images'] = $imagePaths;
        Alat::create($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alat berhasil ditambahkan']);
        }
        return back()->with('success', 'Alat berhasil ditambahkan');
    }

    public function update(Request $request, Alat $alat)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama_alat' => 'required',
            'stok' => 'required|integer',
            'harga_sewa_per_hari' => 'required|numeric',
            'denda_per_hari' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'images' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

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

            $images = $request->file('images');
            // Ensure it's an array even if single file
            if (!is_array($images) && $images) {
                $images = [$images];
            }
            
            if (is_array($images)) {
                foreach ($images as $image) {
                    if ($image && $image->isValid()) {
                        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadDir, $filename);
                        $imagePaths[] = 'uploads/alats/' . $filename;
                    }
                }
            }
        }

        $data['images'] = $imagePaths;
        $alat->update($data);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alat berhasil diperbarui']);
        }
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
