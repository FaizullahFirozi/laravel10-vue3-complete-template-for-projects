<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->only(['name', 'email', 'avatar']);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' =>  ['required'],
            'email' =>  ['required', 'email' , Rule::unique('users')->ignore($request->user()->id)],
        ]);


        $request->user()->update($validated);

        return response()->json(['success' => true]);
    }




    public function uploadImage(Request $request)
    {
       if($request->hasFile('profile_picture')){ 

        $previousPath = $request->user()->getRawOriginal('avatar');
        
        $link = Storage::put('/users_avatar', $request->file('profile_picture'));

        $request->user()->update(['avatar' =>  $link]);

        Storage::delete($previousPath);

        // return response()->json(['message' => 'Profile hahahah Picture Uploaded Successfully1']);
       }
    }


    public function changePassword(Request $request, UpdateUserPassword $updater)
     {
            $updater->update(

                auth()->user(),
                [
                    'current_password' => $request->currentPassword,
                    'password' => $request->password,
                    'password_confirmation' => $request->passwordConfirmatiton,
                ]
            );

            return response()->json(['message' => 'Password Changed Successfully']);
    }
}
