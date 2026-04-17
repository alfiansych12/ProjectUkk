<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FormatPhoneTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use FormatPhoneTrait;

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        // Validasi nilai per_page hanya yang diizinkan
        $allowedPerPage = [5, 10, 15, 20];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        // Paginate dengan appends untuk mempertahankan query parameter
        $users = User::latest()->paginate($perPage)->appends(['per_page' => $perPage]);
        return view('admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|min:10|max:20',
            'role' => 'required|in:admin,petugas,peminjam',
            'password' => 'required|string|min:8',
        ]);

        // Format nomor ke format internasional (62xxx)
        $phone = $this->formatWhatsApp($request->phone);

        // Auto-generate email dari username
        $email = $request->username . '@pinjamala.local';

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'phone' => $phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => 'User berhasil ditambahkan']);
        }

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'phone' => 'required|string|min:10|max:20',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        // Format nomor ke format internasional (62xxx)
        $phone = $this->formatWhatsApp($request->phone);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $phone,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => 'User berhasil diperbarui']);
        }

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}
