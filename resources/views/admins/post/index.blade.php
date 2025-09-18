@extends('layouts.admin', ['title' => 'List of posts'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">List of {{ request('trash')? 'trashed' : '' }} posts</h2>

    <div class="bg-white p-6 rounded shadow-sm">
        @if (session()->has('message'))
            <input type="hidden" id="message" value="{{ session('message') }}">
        @endif
        <div class="py-4 flex justify-between">
            <div>
                <a href="{{ route('post.create') }}" class="p-2 bg-green-700 text-white rounded-md cursor-pointer">Add Post</a>
                @if (request()->has('trash'))
                    <a href="{{ route('post.index') }}" class="p-2 bg-cyan-700 text-white rounded-md cursor-pointer">All Posts</a>
                @else
                    <a href="{{ route('post.index', ['trash' => 'active']) }}" class="p-2 bg-gray-700 text-white rounded-md cursor-pointer">Trashed Posts</a>
                @endif
            </div>
            <form action="{{ route('post.index') }}" class="flex gap-2">
                <input value="{{ request('search') }}" type="text" name="search" placeholder="Search in to post table" class="p-2 bg-gray-100 rounded-md">
                @if (request()->has('trash'))
                    <input type="hidden" name="trash" value="active">
                @endif
                <button type="submit" class="p-2 bg-blue-700 text-white rounded-md cursor-pointer">Search</button>
                @if (request()->has('search'))
                    <a href="{{ route('post.index') }}" class="p-2 bg-cyan-700 text-white rounded-md cursor-pointer">Remove Search</a>
                @endif
            </form>
        </div>
        @if($posts->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                        <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Title</th>
                        <th class="py-3 px-4 border-b">Status</th>
                        <th class="py-3 px-4 border-b">User</th>
                        <th class="py-3 px-4 border-b">Comment Count</th>
                        <th class="py-3 px-4 border-b">Created At</th>
                        <th class="py-3 px-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @foreach ($posts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $post->id }}</td>
                                <td class="py-3 px-4 border-b">{{ $post->title }}</td>
                                <td class="py-3 px-4 border-b text-center">
                                    @if ($post->status)
                                        <span class="text-white bg-green-700 py-1 px-4 rounded-md">Active</span>
                                    @else
                                        <span class="text-white bg-red-700 py-1 px-4 rounded-md">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">{{ $post->user->name . ' ' . $post->user->family }}</td>
                                <td class="py-3 px-4 border-b">
                                    @if ($post->comments_count)
                                        {{ $post->comments_count }} <small>Comments</small>
                                    @else
                                        <small class="text-red-700">Without Comment</small>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">{{ $post->created_at->format('d M Y - l | H:m') }}</td>
                                <td class="py-3 px-4 border-b text-right space-x-2 flex">
                                    @if (request('trash'))
                                        @can('restore', $post)
                                            <button onclick="restoreItem({{ $post->id }}, '{{ $post->title }}')" type="button" class="text-green-500 hover:underline cursor-pointer">Restore</button>
                                            <form id="restore-item-{{ $post->id }}" action="{{ route('post.restore', ['post' => $post->id]) }}" method="POST">
                                                @method("PATCH")
                                                @csrf
                                            </form>
                                        @endcan

                                        @can('forceDelete', $post)
                                            <button onclick="forceDeleteItem({{ $post->id }}, '{{ $post->title }}')" type="button" class="text-red-500 hover:underline cursor-pointer">Force Delete</button>
                                            <form id="force-delete-item-{{ $post->id }}" action="{{ route('post.force.delete', ['post' => $post->id]) }}" method="POST">
                                                @method("DELETE")
                                                @csrf
                                            </form>
                                        @endcan
                                    @else
                                        @can('update', $post)
                                            <a href="{{ route('post.edit', ['post' => $post->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                        @endcan
                                        
                                        @can('delete', $post)
                                            <button onclick="deleteItem({{ $post->id }}, '{{ $post->title }}')" type="button" class="text-red-500 hover:underline cursor-pointer">Delete</button>
                                            <form id="delete-item-{{ $post->id }}" action="{{ route('post.destroy', ['post' => $post->id]) }}" method="POST">
                                                @method("DELETE")
                                                @csrf
                                            </form>
                                        @endcan

                                        @can('change', $post)
                                            <button onclick="changeItem({{ $post->id }}, '{{ $post->title }}')" type="button" class="text-cyan-500 hover:underline cursor-pointer">Change</button>
                                            <form id="change-item-{{ $post->id }}" action="{{ route('post.change', ['post' => $post->id]) }}" method="POST">
                                                @method("patch")
                                                @csrf
                                            </form>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="py-3">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <div class="p-4 text-center bg-gray-100">
                Does not exists any post
            </div>
        @endif
    </div>
</main>
@endsection