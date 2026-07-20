<?php
namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = CompanyProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['name' => Auth::user()->name, 'level' => 1, 'xp' => 0]
        );
        return view('dashboard.employer.profile.index', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'industry' => 'nullable|string',
            'website' => 'nullable|url',
            'year_founded' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $profile = CompanyProfile::firstOrCreate(['user_id' => Auth::id()]);
        
        // Update company name
        $profile->name = $request->company_name;

        // Update account name (users table)
        Auth::user()->update(['name' => $request->account_name]);

        $profile->description = $request->description;
        $profile->industry = $request->industry;
        $profile->website = $request->website;
        $profile->year_founded = $request->year_founded;

        
        if ($request->hasFile('logo')) {
            $profile->logo = $this->convertToWebp($request->file('logo'), 'profiles/logos');
        }

        
        if ($request->hasFile('cover_photo')) {
            $profile->cover_photo = $this->convertToWebp($request->file('cover_photo'), 'profiles/covers');
        }

        
        if (!$profile->wasRecentlyCreated && $profile->isDirty()) {
            $profile->xp += 50;
            if ($profile->xp >= (500 * $profile->level)) {
                $profile->level += 1;
            }
        }

        $profile->save();

        return back()->with('success', 'Profil Perusahaan berhasil diperbarui! (+50 XP)');
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
