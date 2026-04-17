<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LoanController extends Controller
{
    // List for Peminjam
    public function index()
    {
        $loans = Peminjaman::with('alat')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('peminjam.pinjaman', compact('loans'));
    }

    // List for Admin/Petugas
    public function manage()
    {
        $loans = Peminjaman::with(['alat', 'user'])
            ->orderByRaw("status = 'pending' DESC, status = 'menunggu_pengembalian' DESC")
            ->latest()
            ->paginate(15);

        $users = \App\Models\User::where('role', 'peminjam')->get();
        $alats = Alat::where('stok', '>', 0)->get();

        return view('petugas.peminjaman', compact('loans', 'users', 'alats'));
    }

    // Submit loan request (Peminjam version)
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alats,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
            'jumlah' => 'required|integer|min:1',
        ]);

        $alat = Alat::findOrFail($request->alat_id);
        if ($alat->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => $request->alat_id,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        AktivitasLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Request Pinjam',
            'deskripsi' => 'User mengajukan peminjaman alat ID: ' . $request->alat_id,
        ]);

        return redirect()->route('loans.index')->with('success', 'Permohonan peminjaman berhasil dikirim');
    }

    // Submit loan request (Offline/Admin version)
    public function storeOffline(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:191',
            'borrower_whatsapp' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
            'otp_code' => 'required|string|digits:6',
            'alat_id' => 'required|exists:alats,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'jumlah' => 'required|integer|min:1',
        ]);

        $whatsapp = $this->formatWhatsApp($request->borrower_whatsapp);

        $expectedOtp = (string) session("offline_otp.{$whatsapp}");
        $inputOtp = (string) $request->otp_code;

        \Log::info('Verifying OTP', [
            'whatsapp' => $whatsapp,
            'expected' => $expectedOtp,
            'input' => $inputOtp,
            'all_session' => session()->all(),
        ]);

        if (!$expectedOtp || $expectedOtp !== $inputOtp) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa. Silakan kirim ulang OTP.');
        }

        $alat = Alat::findOrFail($request->alat_id);
        if ($alat->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        // Create record
        $loan = Peminjaman::create([
            'user_id' => Auth::id(),
            'borrower_name' => $request->borrower_name,
            'borrower_whatsapp' => $request->borrower_whatsapp,
            'otp_verified_at' => now(),
            'alat_id' => $request->alat_id,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'jumlah' => $request->jumlah,
            'status' => 'borrowed',
        ]);

        // Manually decrement stock because trigger only fires on UPDATE
        $alat->decrement('stok', $request->jumlah);

        session()->forget("offline_otp.{$whatsapp}");

        AktivitasLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Pinjam Offline',
            'deskripsi' => 'Admin mencatat peminjaman offline untuk ' . $request->borrower_name,
        ]);

        return back()->with('success', 'Peminjaman offline berhasil dicatat');
    }

    public function sendOfflineOtp(Request $request)
    {
        $request->validate([
            'borrower_whatsapp' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
        ]);

        $token = env('FONNTE_TOKEN');

        if (!$token) {
            return response()->json(['message' => 'Token FONNTE_TOKEN belum dikonfigurasi di .env'], 500);
        }

        $otp = (string) random_int(100000, 999999);
        $whatsapp = $this->formatWhatsApp($request->borrower_whatsapp);

        try {
            // Store as string to avoid type issues
            session(["offline_otp.{$whatsapp}" => $otp]);
            session()->save(); // Explicitly save session for AJAX

            \Log::info('Stored OTP in session', [
                'whatsapp' => $whatsapp,
                'otp' => $otp,
                'session_id' => session()->getId()
            ]);
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $whatsapp,
                        'message' => "Kode OTP Peminjaman: {$otp}",
                    ]);

            \Log::info('Fonnte OTP Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'phone' => $whatsapp,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                session(["offline_otp.{$whatsapp}" => $otp]);
                return response()->json(['message' => 'Kode OTP telah dikirim ke WhatsApp.']);
            } elseif (isset($responseData['status']) && $responseData['status'] === false) {
                return response()->json([
                    'message' => 'Gagal mengirim OTP. Reason: ' . ($responseData['reason'] ?? 'Unknown error'),
                ], 422);
            }

            return response()->json([
                'message' => 'Gagal mengirim OTP. Status: ' . $response->status(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Fonnte OTP Error', [
                'error' => $e->getMessage(),
                'phone' => $whatsapp,
            ]);

            return response()->json([
                'message' => 'Error mengirim OTP: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Peminjaman $loan)
    {
        $rules = [
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'jumlah' => 'required|integer|min:1',
        ];

        // Jika ini peminjaman offline, validasi juga nama & WA
        if ($loan->borrower_name !== null || $request->has('borrower_name')) {
            $rules['borrower_name'] = 'required|string|max:191';
            $rules['borrower_whatsapp'] = ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'];
        }

        $request->validate($rules);

        $data = $request->only(['tgl_pinjam', 'tgl_kembali_rencana', 'jumlah']);

        if ($request->has('borrower_name')) {
            $data['borrower_name'] = $request->borrower_name;
            $data['borrower_whatsapp'] = $this->formatWhatsApp($request->borrower_whatsapp);
        }

        // Jika status is borrowed, we might need to adjust stock
        if ($loan->status === 'borrowed' && $loan->jumlah != $request->jumlah) {
            $diff = $request->jumlah - $loan->jumlah;
            $alat = $loan->alat;
            if ($alat->stok < $diff) {
                return back()->with('error', 'Stok tidak mencukupi untuk perubahan jumlah');
            }
            $alat->decrement('stok', $diff);
        }

        $loan->update($data);

        return back()->with('success', 'Data peminjaman berhasil diperbarui');
    }

    public function destroy(Peminjaman $loan)
    {
        // Return stock if it was borrowed
        if ($loan->status === 'borrowed') {
            $loan->alat->increment('stok', $loan->jumlah);
        }

        $loan->delete();
        return back()->with('success', 'Data peminjaman berhasil dihapus');
    }

    public function approve(Peminjaman $loan)
    {
        if ($loan->status !== 'pending')
            return back();

        $loan->update(['status' => 'borrowed']);

        AktivitasLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Setujui Pinjam',
            'deskripsi' => 'Petugas menyetujui peminjaman ID: ' . $loan->id,
        ]);

        return back()->with('success', 'Peminjaman disetujui, stok alat otomatis berkurang (via Trigger)');
    }

    public function reject(Peminjaman $loan)
    {
        if ($loan->status !== 'pending')
            return back();

        $loan->update(['status' => 'rejected']);

        AktivitasLog::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tolak Pinjam',
            'deskripsi' => 'Petugas menolak peminjaman ID: ' . $loan->id,
        ]);

        return back()->with('success', 'Peminjaman ditolak');
    }

    // Return tool using Stored Procedure
    public function returnTool(Request $request, Peminjaman $loan)
    {
        $request->validate([
            'tgl_kembali_real' => 'required|date|after_or_equal:' . $loan->tgl_pinjam,
        ]);

        try {
            DB::statement("CALL sp_kembalikan_alat(?, ?, ?)", [
                $loan->id,
                $request->tgl_kembali_real,
                Auth::id()
            ]);

            // Refresh loan data to get latest status and denda from DB
            $loan->refresh();

            AktivitasLog::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Pengembalian',
                'deskripsi' => 'Petugas memproses pengembalian ID: ' . $loan->id,
            ]);

            // KIRIM NOTIFIKASI WHATSAPP JIKA ADA DENDA
            if ($loan->denda > 0) {
                $whatsapp = $loan->borrower_whatsapp ?: optional($loan->user)->phone;
                
                if ($whatsapp) {
                    $whatsapp = $this->formatWhatsApp($whatsapp);
                    $token = env('FONNTE_TOKEN');
                    $borrowerName = $loan->borrower_name ?: optional($loan->user)->name;
                    $alatName = $loan->alat->nama_alat;
                    $totalDenda = number_format($loan->denda, 0, ',', '.');
                    
                    $message = "Halo *{$borrowerName}*,\n\n";
                    $message .= "Kami telah menerima pengembalian alat: *{$alatName}*.\n";
                    $message .= "Dikarenakan keterlambatan, Anda dikenakan denda sebesar:\n";
                    $message .= "*Rp {$totalDenda}*\n\n";
                    $message .= "Harap segera melakukan pembayaran ke petugas. Terima kasih.";

                    try {
                        Http::withHeaders([
                            'Authorization' => $token,
                        ])->asForm()->post('https://api.fonnte.com/send', [
                            'target' => $whatsapp,
                            'message' => $message,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Gagal kirim WA denda: ' . $e->getMessage());
                    }
                }
            }

            return back()->with('success', 'Alat berhasil dikembalikan. Notifikasi denda telah dikirim (jika ada)');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    // Report for Petugas/Admin
    public function report()
    {
        $loans = Peminjaman::with(['alat', 'user'])
            ->whereIn('status', ['borrowed', 'returned'])
            ->latest()
            ->paginate(50);

        return view('petugas.laporan', compact('loans'));
    }

    private function formatWhatsApp($number)
    {
        $whatsapp = preg_replace('/\D+/', '', $number);
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        } elseif (str_starts_with($whatsapp, '620')) {
            $whatsapp = '62' . substr($whatsapp, 3);
        } elseif (!str_starts_with($whatsapp, '62')) {
            $whatsapp = '62' . $whatsapp;
        }
        return $whatsapp;
    }

    public function requestReturn(Peminjaman $loan)
    {
        if ($loan->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($loan->status !== 'borrowed') {
            return back()->with('error', 'Hanya alat yang sedang dipinjam yang dapat diajukan pengembaliannya.');
        }

        $loan->update(['status' => 'menunggu_pengembalian']);

        AktivitasLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'aktivitas' => 'Request Pengembalian',
            'deskripsi' => 'User mengajukan pengembalian peminjaman ID: ' . $loan->id,
        ]);

        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim. Menunggu konfirmasi petugas.');
    }
}
