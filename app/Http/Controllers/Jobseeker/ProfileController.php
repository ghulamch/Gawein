<?php
namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SeekerProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = SeekerProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['title' => '', 'profile_completion' => 10]
        );
        return view('dashboard.jobseeker.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'skills' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        $profile = SeekerProfile::firstOrCreate(['user_id' => $user->id]);
        
        $profile->title = $request->title;
        $profile->location = $request->location;
        $profile->about = $request->about;
        $profile->skills = $request->skills;

        if ($request->hasFile('avatar')) {
            $profile->avatar = $this->convertToWebp($request->file('avatar'), 'profiles/avatars');
        }

        if ($request->hasFile('resume')) {
            $filename = Str::random(40) . '.' . $request->file('resume')->getClientOriginalExtension();
            $request->file('resume')->move(public_path('uploads/resumes'), $filename);
            $profile->resume = 'resumes/' . $filename;
        }

        
        $completion = 10; 
        if ($profile->title) $completion += 15;
        if ($profile->location) $completion += 15;
        if ($profile->about) $completion += 20;
        if ($profile->skills) $completion += 20;
        if ($profile->resume) $completion += 20;
        $profile->profile_completion = $completion;

        $profile->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    private function convertToWebp($file, $path)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $image = null;

        if (in_array($extension, ['jpg', 'jpeg'])) {
            $image = @imagecreatefromjpeg($file->getRealPath());
        } elseif ($extension == 'png') {
            $image = @imagecreatefrompng($file->getRealPath());
            if ($image) {
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }
        }

        if ($image) {
            $filename = Str::random(40) . '.webp';
            $fullPath = public_path('uploads/' . $path);
            
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            imagewebp($image, $fullPath . '/' . $filename, 85); 
            imagedestroy($image);
            
            return $path . '/' . $filename;
        }

        
        $filename = Str::random(40) . '.' . $extension;
        $file->move(public_path('uploads/' . $path), $filename);
        return $path . '/' . $filename;
    }
}
