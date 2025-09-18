@extends('layouts.admin', ['title' => 'List of categories'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">List of categories</h2>

    <div class="bg-white p-6 rounded shadow-sm">
        @if (session()->has('message'))
            <input type="hidden" id="message" value="{{ session('message') }}">
        @endif
        <div class="py-4 flex justify-between">
            <div>
                <a href="{{ route('category.create') }}" class="p-2 bg-green-700 text-white rounded-md cursor-pointer">Add Category</a>
            </div>
            <form action="{{ route('category.index') }}" class="flex gap-2">
                <input value="{{ request('search') }}" type="text" name="search" placeholder="Search in to post table" class="p-2 bg-gray-100 rounded-md">
                <button type="submit" class="p-2 bg-blue-700 text-white rounded-md cursor-pointer">Search</button>
                @if (request()->has('search'))
                    <a href="{{ route('category.index') }}" class="p-2 bg-cyan-700 text-white rounded-md cursor-pointer">Remove Search</a>
                @endif
            </form>
        </div>
        @if($categories->count())
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded shadow-sm">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-600">
                        <tr>
                        <th class="py-3 px-4 border-b">#</th>
                        <th class="py-3 px-4 border-b">Name</th>
                        <th class="py-3 px-4 border-b">Post Count</th>
                        <th class="py-3 px-4 border-b">Created At</th>
                        <th class="py-3 px-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b">{{ $category->id }}</td>
                                <td class="py-3 px-4 border-b">{{ $category->name }}</td>
                                <td class="py-3 px-4 border-b">
                                    @if ($category->posts_count)
                                        {{ $category->posts_count }} <small>Posts</small>
                                    @else
                                        <small class="text-red-700">Without Post</small>
                                    @endif
                                </td>
                                <td class="py-3 px-4 border-b">{{ $category->created_at->format('d M Y - l | H:m') }}</td>
                                <td class="py-3 px-4 border-b text-right space-x-2 flex">
                                    <a href="{{ route('category.edit', ['category' => $category->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                    <button onclick="deleteItem({{ $category->id }}, '{{ $category->title }}')" type="button" class="text-red-500 hover:underline cursor-pointer">Delete</button>
                                    <form id="delete-item-{{ $category->id }}" action="{{ route('category.destroy', ['category' => $category->id]) }}" method="POST">
                                        @method("DELETE")
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="py-3">
                    {{ $categories->links() }}
                </div>
            </div>
        @else
            <div class="p-4 text-center bg-gray-100">
                Does not exists any category
            </div>
        @endif
    </div>
</main>
@endsection