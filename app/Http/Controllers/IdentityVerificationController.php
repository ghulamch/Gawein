<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class IdentityVerificationController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'ktp_image' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $user = Auth::user();

        if ($request->hasFile('ktp_image')) {
            $file = $request->file('ktp_image');
            $filename = 'ktp_' . $user->id . '_' . time() . '.webp';
            
            
            $manager = new ImageManager(new Driver());
            $image = $manager->decode($file);
            
            
            $image->scaleDown(1200, 1200);
            
            
            $path = 'public/ktp/' . $filename;
            Storage::put($path, (string) $image->encode(new \Intervention\Image\Encoders\WebpEncoder(80)));

            
            if ($user->ktp_path && Storage::exists('public/' . $user->ktp_path)) {
                Storage::delete('public/' . $user->ktp_path);
            }

            $user->ktp_path = 'ktp/' . $filename;
            $user->verification_status = 'pending';
            $user->save();

            return back()->with('success', 'KTP berhasil diunggah. Mohon tunggu verifikasi dari Admin.');
        }

        return back()->with('error', 'Gagal mengunggah KTP.');
    }
}
