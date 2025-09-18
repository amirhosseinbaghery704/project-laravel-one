@extends('layouts.admin', ['title' => 'List of users'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">List of users</h2>

    <div class="bg-white p-6 rounded shadow-sm">
        @if (session()->has('message'))
            <input type="hidden" id="message" value="{{ session('message') }}">
        @endif
        <div class="py-4 flex justify-between">
            <div>
                <a href="{{ route('user.create') }}" class="p-2 bg-green-700 text-white rounded-md cursor-pointer">Add User</a>
            </div>
            <form action="{{ route('user.index') }}" class="flex gap-2">
                <input value="{{ request('search') }}" type="text" name="search" placeholder="Search in to post table" class="p-2 bg-gray-100 rounded-md">
                <button type="submit" class="p-2 bg-blue-700 text-white rounded-md cursor-pointer">Search</button>
                @if (request()->has('search'))
                    <a href="{{ route('user.index') }}" class="p-2 bg-cyan-700 text-white rounded-md cursor-pointer">Remove Search</a>
                @endif
            </form>
        </div>
        @if($users->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                        <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Name</th>
                        <th class="py-3 px-4 border-b">Family</th>
                        <th class="py-3 px-4 border-b">Email</th>
                        <th class="py-3 px-4 border-b">Role</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b">Post Count</th>
                        <th class="py-3 px-4 border-b">Created At</th>
                        <th class="py-3 px-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $user->id }}</td>
                                <td class="py-3 px-4 border-b">{{ $user->name }}</td>
                                <td class="py-3 px-4 border-b">{{ $user->family }}</td>
                                <td class="py-3 px-4 border-b">{{ $user->email }}</td>
                                <td class="py-3 px-4 border-b text-center">
                                    <span class="text-white {{ $user->isAdmin()? 'bg-blue-400' : 'bg-gray-400' }} py-1 px-4 rounded-md">{{ $user->role }}</span>
                                </td>
                                <td class="py-3 px-4 border-b text-center">
                                    @if ($user->status)
                                        <span class="text-white bg-green-700 py-1 px-4 rounded-md">Active</span>
                                    @else
                                        <span class="text-white bg-red-700 py-1 px-4 rounded-md">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">
                                    @if ($user->posts_count)
                                        {{ $user->posts_count }} <small>Posts</small>
                                    @else
                                        <small class="text-red-700">Without Post</small>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">{{ $user->created_at->format('d M Y - l | H:m') }}</td>
                                <td class="py-3 px-4 border-b text-right space-x-2 flex">
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <button onclick="deleteItem({{ $user->id }}, '{{ $user->fullname }}')" type="button" class="text-red-500 hover:underline cursor-pointer">Delete</button>
                                    <form id="delete-item-{{ $user->id }}" action="{{ route('user.destroy', ['user' => $user->id]) }}" method="POST">
                                        @method("DELETE")
                                        @csrf
                                    </form>

                                    <button onclick="changeItem({{ $user->id }}, '{{ $user->fullname }}')" type="button" class="text-cyan-500 hover:underline cursor-pointer">Change</button>
                                    <form id="change-item-{{ $user->id }}" action="{{ route('user.change', ['user' => $user->id]) }}" method="POST">
                                        @method("patch")
                                        @csrf
                                    </form>

                                    <button onclick="resetItem({{ $user->id }}, '{{ $user->fullname }}')" type="button" class="text-orange-500 hover:underline cursor-pointer">Reset Pass</button>
                                    <form id="reset-item-{{ $user->id }}" action="{{ route('user.reset', ['user' => $user->id]) }}" method="POST">
                                        @method("patch")
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="py-3">
                    {{ $users->links() }}
                </div>
            </div>
        @else
            <div class="p-4 text-center bg-gray-100">
                Does not exists any user
            </div>
        @endif
    </div>
</main>
@endsection