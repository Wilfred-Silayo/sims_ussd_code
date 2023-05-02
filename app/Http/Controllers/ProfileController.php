<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\NoCacheMiddleware;

class ProfileController extends Controller
{   
     //Disable caches using no cache middleware
     public function __construct()
     {
         $this->middleware(NoCacheMiddleware::class);
     }

    public function profileUpdate(){
        $user = Auth::user();
        return view('student.full_profile')->with('user',$user);
       }
    
        public function storeProfile(Request $request)
        {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:2048'], // Max file size is 2MB
        ]);
    
        $user = Auth::user();
        $file = $request->file('profile_photo');
        $extension = $file->getClientOriginalExtension();
        $filename = $user->username . '_' . time() . '.' . $extension;
        $path = $file->storeAs('public', $filename);
        
        // Check if the new photo is not equal to the default photo "user.png"
        if ($user->profile_photo_path !== 'user.png') {
            // Delete the old photo
            Storage::delete('public/' . $user->profile_photo_path);
        }
        
        $user->profile_photo_path = $filename;
        $user->save();
        return back()->with('info', 'Profile Photo Updated Successfully');
        }
    
}
