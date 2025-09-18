@extends('layouts.admin', ['title' => 'Create New User'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">Create New User</h2>
    <div class="bg-white p-6 rounded shadow-sm">
        <form action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
            @method('POST')
            @csrf
            <div class="grid grid-cols-2 gap-2">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter name"
                        value="{{ old('name') }}">
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
                        value="{{ old('family') }}">
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
                        value="{{ old('email') }}">
                    @error('email')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="role">Role</label>
                    <select 
                        name="role" 
                        id="role" 
                        class="multiple-select w-full mt-2 mb-1">
                        @foreach (App\Enums\UserRoleEnum::cases() as $item)
                            <option value="{{ $item->value }}" {{ old('role') == $item->value ? 'selected' : ''}}>{{ $item->name }}</option>
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
                        class="multiple-select w-full mt-2 mb-1">

                        <option value="active" {{ old('status') == 'active' ? 'selected' : ''}}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : ''}}>Inactive</option>
                    </select>
                    @error('status')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="bg-green-700 text-white rounded-md p-2 cursor-pointer">Save User</button>
            </div>
        </form>
    </div>
</main>
@endsection