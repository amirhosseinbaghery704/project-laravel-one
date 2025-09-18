@extends('layouts.template', ['title' => 'Register Page'])

@section('main')
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm text-center">
        <div class="text-gray-800 font-semibold">
            <span class="text-yellow-500 text-xl">&lt;YELO&gt;</span> Code
        </div>
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Register to website</h2>
    </div>
  
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" action="{{ route('register.user') }}" method="POST">
            @method('POST')
            @csrf
            <div>
                <label for="name" class="block text-sm/6 font-medium text-gray-900">Your name</label>
                <div class="mt-2">
                    <input value="{{ old('name') }}" type="text" name="name" id="name" autocomplete="name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @error('name')
                        <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="family" class="block text-sm/6 font-medium text-gray-900">Your family</label>
                <div class="mt-2">
                    <input value="{{ old('family') }}" type="text" name="family" id="family" autocomplete="family" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @error('family')
                        <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-900">Your email address</label>
                <div class="mt-2">
                    <input value="{{ old('email') }}" type="text" name="email" id="email" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @error('email')
                        <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm/6 font-medium text-gray-900">Your password</label>
                <div class="mt-2">
                    <input type="password" name="password" id="password" autocomplete="current-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @error('password')
                        <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div>
                <label for="password_confirmation " class="block text-sm/6 font-medium text-gray-900">Repeat password</label>
                <div class="mt-2">
                    <input type="password" name="password_confirmation" id="password_confirmation " autocomplete="current-password" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    @error('password_confirmation ')
                        <div class="text-red-500 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
    
            <div>
                <button type="submit" class="flex w-full justify-center rounded-md  px-3 py-1.5 text-sm/6 font-semibold shadow-xs bg-yellow-500 hover:bg-yellow-900 text-white cursor-pointer">Sign in</button>
            </div>
        </form>
  
        <p class="mt-10 text-center text-sm/6 text-gray-500">
            Your are member?
            <a href="{{ route('login') }}" class="font-semibold text-yellow-500 hover:text-yellow-900">Login to website</a>
        </p>
    </div>
  </div>
@endsection