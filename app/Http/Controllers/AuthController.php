<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Jobs\ResizeAvatarJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        return View::make('authenticates.login');
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|min:6|string'
        ]);
    
        // Create a unique rate limiter key using the IP + email
        $throttleKey = Str::lower($request->input('email')).'|'.$request->ip();
    
        // Limit to 5 attempts per minute
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => [__('Too many login attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($throttleKey)
                ])],
            ]);
        }
    
        if (! Auth::attempt($data)) {
            RateLimiter::hit($throttleKey, 600); // count failed attempt
    
            throw ValidationException::withMessages([
                'email' => ['Invalid credential'],
            ]);
        }
    
        RateLimiter::clear($throttleKey); // successful login clears the limiter
    
        $user = Auth::user();
    
        if (! $user->status) {
            Auth::logout();
    
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive'],
            ]);
        }
    
        return Redirect::route('dashboard');
    }

    public function register()
    {
        return View::make('authenticates.register');
    }

    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'family' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|max:100|confirmed'
        ]);

        $data['status'] = true;
        $data['role'] = UserRoleEnum::USER;
        $user = User::create($data);

        Auth::login($user);
        return Redirect::route('dashboard');
    }

    public function logout()
    {
        Auth::logout();;
        
        return Redirect::route('login');
    }

    public function profile()
    {
        $user = Auth::user();

        return View::make('admins.profile.edit', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'family' => 'required|string|min:3|max:100',
            'avatar' => 'nullable|image',
            'password' => 'nullable|string|min:8|max:100|confirmed'
        ]);

        /** @var  User */
        $user = Auth::user();
        $user->name = $data['name'];
        $user->family = $data['family'];

        if (isset($data['password']))
            $user->password = $data['password'];

        if (isset($data['avatar'])) {
            $user->avatar = 'storage/' . $data['avatar']->storeAs('avatar', md5($user->email) . '.png');

            ResizeAvatarJob::dispatch($user);
        }

        $user->save();

        return redirect()->back()->with('message', "Your profile has been updated.");
    }
}
