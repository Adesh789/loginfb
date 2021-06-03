<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Exception;
use App\User;
use Illuminate\Support\Facades\Auth;


class FacebookController extends Controller
{


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        
        $user = Socialite::driver('facebook')->stateless()->user();

        $finduser = User::where('facebook_id', $user->id)->first();

        if($finduser){
            Auth::login($finduser);
            return redirect()->intended('/home');
        }else{
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'facebook_id'=> $user->id,
                'password' => encrypt('123456dummy')
            ]);
            Auth::login($newUser);

            return redirect('/home');
        }
        
    }
}
