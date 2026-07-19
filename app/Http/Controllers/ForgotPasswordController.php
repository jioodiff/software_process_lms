<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Simpan OTP di cache selama 15 menit
        Cache::put('otp_' . $user->email, $otp, now()->addMinutes(15));

        // Tembak Webhook n8n
        $webhookUrl = config('services.n8n.webhook_url');
        if ($webhookUrl) {
            try {
                Http::timeout(5)->post($webhookUrl, [
                    'event' => 'user.forgot_password',
                    'student_email' => $user->email,
                    'student_name' => $user->nama_lengkap,
                    'otp' => $otp,
                    'timestamp' => now()->toISOString(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('N8N Webhook failed (otp): ' . $e->getMessage());
            }
        }

        return back()->with('otp_sent_to', $user->email)->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp !== $request->otp) {
            return back()->with('otp_sent_to', $request->email)
                         ->with('error', 'Kode OTP salah atau sudah kadaluarsa.');
        }

        // Hapus OTP dari cache
        Cache::forget('otp_' . $request->email);

        // Set session validasi untuk mengizinkan ganti password
        session(['reset_password_email' => $request->email]);

        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if (!session('reset_password_email')) {
            return redirect()->route('password.request')->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    public function updatePassword(Request $request)
    {
        $email = session('reset_password_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Sesi telah berakhir. Silakan ulangi.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.'
        ]);

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            
            AuditLog::record('User', 'RESET_PASSWORD_SELF', (string) $user->id, [], ['password' => 'reset via otp']);
        }

        session()->forget('reset_password_email');

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan masuk.');
    }
}
