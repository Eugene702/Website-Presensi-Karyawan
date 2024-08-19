<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = User::find(Auth::id());
    
        // Hapus gambar lama jika ada
        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }
    
        // Simpan gambar baru
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    
        // Simpan nama file dalam database
        $user->profile_picture = $path;
        $user->save(); // Pastikan perubahan disimpan
    
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
    
}

