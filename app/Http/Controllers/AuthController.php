<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()
            ->route('show.login')
            ->with('success', 'Account created successfully. Please login.');
    }    

    public function Login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if(Auth::attempt($validated)){
            $request->session()->regenerate();

            return redirect()
                ->route('show.dashobard')
                ->with('success','Welcome Back!');
        }
        return back()->withErrors([
            'email'=>'Invalid username or password',
        ])->onlyInput('email');
    }

    public function Logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()
            ->route('show.login')
            ->with('success','Logout Successfully');
    }

}
