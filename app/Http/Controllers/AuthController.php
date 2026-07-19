<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'mahasiswa.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('login')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak percobaan gagal. Silakan coba lagi dalam $seconds detik.")->withInput();
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nim';
        $credentials = [$loginField => $request->login, 'password' => $request->password];

        // Check if user exists and is active
        $user = User::where($loginField, $request->login)->first();

        if ($user && !$user->is_active) {
            return back()->with('error', 'Akun Anda telah dinonaktifkan. Hubungi admin.')->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $lastLoginLog = AuditLog::where('user_id', auth()->id())
                ->where('aksi', 'LOGIN')
                ->latest('timestamp')
                ->first();
            
            $lastLoginTime = $lastLoginLog ? $lastLoginLog->timestamp->format('Y-m-d H:i:s') : 'Belum pernah login';

            AuditLog::record(
                'Auth', 
                'LOGIN', 
                (string) auth()->id(),
                ['login_terakhir' => $lastLoginTime, 'status' => 'belum login'],
                ['login_terakhir' => now()->format('Y-m-d H:i:s'), 'status' => 'berhasil login']
            );

            if (auth()->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('mahasiswa.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->with('error', 'Email/NIM/NUPTK atau password salah.')->withInput();
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:users,nim',
            'email' => 'required|email|max:100|unique:users,email',
            'no_whatsapp' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
            'program_studi' => 'required|string|max:100',
            'role' => 'required|in:mahasiswa,dosen',
        ], [
            'nim.unique' => 'NIM/NIDN/NUPTK sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'role.required' => 'Peran (Role) wajib dipilih.',
        ]);

        $role = $request->role;

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nim' => $request->nim,
            'email' => $request->email,
            'no_whatsapp' => $request->no_whatsapp,
            'password' => $request->password,
            'role' => $role,
            'program_studi' => $request->program_studi,
            'is_active' => true,
        ]);

        AuditLog::record('Auth', 'CREATE', (string) $user->id, null, $user->only(['nama_lengkap', 'nim', 'email', 'no_whatsapp', 'role', 'program_studi']));

        // Fire webhook to n8n for new registration
        $webhookUrl = config('services.n8n.webhook_url');
        if ($webhookUrl) {
            try {
                \Illuminate\Support\Facades\Http::timeout(5)->post($webhookUrl, [
                    'event' => 'user.registered',
                    'user_id' => $user->id,
                    'student_name' => $user->nama_lengkap, // match existing n8n var naming
                    'student_email' => $user->email,
                    'student_whatsapp' => $user->no_whatsapp,
                    'nim' => $user->nim,
                    'role' => $user->role,
                    'program_studi' => $user->program_studi,
                    'timestamp' => now()->toISOString(),
                ]);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('N8N Webhook failed (register): ' . $e->getMessage());
            }
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout(Request $request)
    {
        AuditLog::record(
            'Auth', 
            'LOGOUT', 
            (string) auth()->id(),
            ['status' => 'sedang login'],
            ['status' => 'berhasil logout', 'waktu_logout' => now()->format('Y-m-d H:i:s')]
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
