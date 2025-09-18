@extends('layouts.admin', ['title' => 'Edit Profile'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">Edit Profile</h2>
    @if (session()->has('message'))
        <input type="hidden" id="message" value="{{ session('message') }}">
    @endif
    <div class="bg-white p-6 rounded shadow-sm">
        <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-3">
                <input id="avatar" type="file" class="hidden" name="avatar"/>
                <img src="{{ asset($user->avatar )}}" class="mt-2 w-36" alt="">
                <label 
                    for="avatar" 
                    class="w-36 cursor-pointer flex items-center justify-center px-4 mt-2 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Select Avatar
                </label>
                @error('avatar')
                    <small class="text-red-500 px-3">{{ $message }}</small>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter name"
                        value="{{ $user->name }}">
                    @error('name')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="family">Family</label>
                    <input 
                        type="text" 
                        name="family" 
                        id="family" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter family"
                        value="{{ $user->family }}">
                    @error('family')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter email"
                        disabled
                        value="{{ $user->email }}">
                    @error('email')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select 
                        name="role" 
                        id="role" 
                        disabled
                        class="multiple-select w-full mt-2 mb-1">
                        @foreach (App\Enums\UserRoleEnum::cases() as $item)
                            <option value="{{ $item->value }}" {{ $user->role == $item ? 'selected' : ''}}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status">Status</label>
                    <select 
                        name="status" 
                        id="status" 
                        disabled
                        class="multiple-select w-full mt-2 mb-1">

                        <option value="active" {{ $user->status ? 'selected' : ''}}>Active</option>
                        <option value="inactive" {{ !$user->status ? 'selected' : ''}}>Inactive</option>
                    </select>
                    @error('status')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <hr class="my-3">
            <div class="w-full text-blue-500 mb-6 text-center">
                if you don`t want to change password, please don`t fill bottom fields.
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter password">
                    @error('password')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter password confirmation" >
                    @error('password_confirmation')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="bg-green-700 text-white rounded-md p-2 cursor-pointer">Update User</button>
            </div>
        </form>
    </div>
</main>
@endsection