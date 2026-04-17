<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FormatPhoneTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    use FormatPhoneTrait;
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'phone' => $this->formatWhatsApp($request->phone),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/', 'unique:'.User::class],
            'otp_code' => ['required', 'string', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $phoneNumber = $request->phone;
        $expectedOtp = session("register_otp.{$phoneNumber}");

        if (!$expectedOtp || (string)$expectedOtp !== (string)$request->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone' => $phoneNumber,
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        session()->forget("register_otp.{$phoneNumber}");

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-]{8,20}$/'],
        ]);

        // Format nomor terlebih dahulu sebelum cek
        $whatsapp = $this->formatWhatsApp($request->phone);

        // Check if phone already exists
        if (User::where('phone', $whatsapp)->exists()) {
            return response()->json(['message' => 'Nomor WhatsApp sudah terdaftar.'], 422);
        }

        $token = env('FONNTE_TOKEN');
        if (!$token) {
            return response()->json(['message' => 'Token FONNTE belum dikonfigurasi.'], 500);
        }

        $otp = (string) random_int(100000, 999999);

        try {
            session(["register_otp.{$whatsapp}" => $otp]);
            session()->save();

            \Log::info('Mencoba mengirim OTP Registrasi', ['whatsapp' => $whatsapp, 'otp' => $otp]);

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $whatsapp,
                'message' => "Kode OTP Registrasi PinjamAlat: {$otp}",
            ]);

            $responseData = $response->json();
            \Log::info('Response Fonnte', ['response' => $responseData]);

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                return response()->json(['message' => 'Kode OTP telah dikirim ke WhatsApp.']);
            } elseif (isset($responseData['status']) && $responseData['status'] === false) {
                return response()->json([
                    'message' => 'Gagal dari Fonnte: ' . ($responseData['reason'] ?? 'Unknown error'),
                ], 422);
            }

            return response()->json(['message' => 'Gagal mengirim OTP. Status: ' . $response->status()], 422);
        } catch (\Exception $e) {
            \Log::error('Fonnte OTP Registration Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
