<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\alert;

class AuthController extends Controller
{
    //to show the UI of the signup
    public function showSignup(){
        return view('auth.signup');
    }    

    //to show the UI of the login
    public function showLogin(){
        return view('auth.login');
    }

    public function Signup(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user = User::create($validated);
        
        Auth::login($user);
    
    }    

    public function Login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            
        }
    }


}
