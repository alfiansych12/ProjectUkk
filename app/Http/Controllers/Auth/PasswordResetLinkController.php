<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FormatPhoneTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    use FormatPhoneTrait;
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset request validating OTP.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'phone' => $this->formatWhatsApp($request->phone),
        ]);

        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
            'otp_code' => ['required', 'string', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phoneNumber = $request->phone;
        $expectedOtp = session("reset_otp.{$phoneNumber}");

        if (!$expectedOtp || (string)$expectedOtp !== (string)$request->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user = User::where('phone', $phoneNumber)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => 'Nomor WhatsApp tidak terdaftar.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        session()->forget("reset_otp.{$phoneNumber}");

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login.');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
        ]);

        $whatsapp = $this->formatWhatsApp($request->phone);

        // Check if phone actually exists
        if (!User::where('phone', $whatsapp)->exists()) {
            return response()->json(['message' => 'Nomor WhatsApp tidak terdaftar.'], 422);
        }

        $token = env('FONNTE_TOKEN');
        if (!$token) {
            return response()->json(['message' => 'Token FONNTE belum dikonfigurasi.'], 500);
        }

        $otp = (string) random_int(100000, 999999);

        try {
            session(["reset_otp.{$whatsapp}" => $otp]);
            session()->save();

            \Log::info('Mencoba mengirim OTP Reset Password', ['whatsapp' => $whatsapp, 'otp' => $otp]);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $whatsapp,
                'message' => "Kode OTP Reset Password PinjamAlat: {$otp}",
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                return response()->json(['message' => 'Kode OTP telah dikirim ke WhatsApp.']);
            } elseif (isset($responseData['status']) && $responseData['status'] === false) {
                return response()->json([
                    'message' => 'Gagal dari Fonnte: ' . ($responseData['reason'] ?? 'Unknown error'),
                ], 422);
            }

            return response()->json(['message' => 'Gagal mengirim OTP. Status: ' . $response->status()], 422);
        } catch (\Exception $e) {
            \Log::error('Fonnte OTP Reset Password Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
