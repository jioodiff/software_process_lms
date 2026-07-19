<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('mahasiswa.profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'nama_lengkap' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:users,nim,' . $user->id,
            'no_whatsapp' => 'required|string|max:20',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->nim = $validated['nim'];
        $user->no_whatsapp = $validated['no_whatsapp'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('mahasiswa.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
