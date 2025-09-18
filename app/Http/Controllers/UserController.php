<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Mail\SendPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        Gate::authorize('admin');
    }

    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'DESC')
            ->where(function ($query) use ($request) {
                return $query->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('family', 'LIKE', "%{$request->search}%")
                    ->orWhere('email', 'LIKE', "%{$request->search}%");
            })
            ->withCount('posts')
            ->paginate(10);

        return View::make('admins.user.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return View::make('admins.user.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'family' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'status' => 'required|in:active,inactive',
            'role' => ['required', Rule::enum(UserRoleEnum::class)]
        ]);

        $data['password'] = $this->generateRandomPassword(8);
        $user = User::create($data);


        Mail::to($user->email)
            ->queue(new SendPasswordMail($user->fullname, $data['password']));
        
        return redirect()->route('user.index')->with('message', "user `{$user->fullname}` has been created.");
    }

    public function destroy(User $user)
    {
        $user->loadCount('posts');
        if (!$user->posts_count) {
            $user->delete();
            return redirect()->back()->with('message', "User `{$user->fullname}` has been deleted.");
        }

        return redirect()->back()->with('message', "User `{$user->fullname}` has many posts. and you are not allowed to delete.");
    }

    public function edit(User $user)
    {
        return View::make('admins.user.edit', [
            'user' => $user
        ]);
    }

    public function update(User $user, Request $request) 
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'family' => 'required|string|min:3|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'status' => 'required|in:active,inactive',
            'role' => ['required', Rule::enum(UserRoleEnum::class)]
        ]);

        $user->update($data);

        return redirect()->route('user.index')->with('message', "User `{$user->fullname}` has been updated.");
    }

    public function change(User $user) 
    {
        $user->status = !$user->status;
        $user->save();

        return redirect()->back()->with('message', "User `{$user->fullname}` has been changed.");
    }

    public function reset(User $user)
    {
        $password = $this->generateRandomPassword(8);
        $user->password = $password;
        $user->save();

        Mail::to($user->email)
            ->queue(new SendPasswordMail($user->fullname, $password));

        return redirect()->back()->with('message', "Password for `{$user->fullname}` has been reseted.");
    }

    private function generateRandomPassword($length = 12): string {
        // Define character sets
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()-_=+[]{}<>?';
    
        // Combine all character sets
        $all = $upper . $lower . $numbers . $symbols;
    
        // Make sure the password contains at least one character from each set
        $password = [
            $upper[random_int(0, strlen($upper) - 1)],
            $lower[random_int(0, strlen($lower) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $symbols[random_int(0, strlen($symbols) - 1)],
        ];
    
        // Fill the remaining length with random characters from the full set
        for ($i = 4; $i < $length; $i++) {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }
    
        // Shuffle to avoid predictable order
        shuffle($password);
    
        // Return the password as a string
        return implode('', $password);
    }
    
}
