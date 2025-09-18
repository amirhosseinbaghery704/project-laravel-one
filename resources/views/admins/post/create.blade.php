@extends('layouts.admin', ['title' => 'Create New Post'])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">Create New Post</h2>
    <div class="bg-white p-6 rounded shadow-sm">
        <form action="{{ route('post.store') }}" enctype="multipart/form-data" method="POST">
            @method('POST')
            @csrf
            <div class="flex justify-between gap-8">
                <div class="w-3/4">
                    <div class="mb-3">
                        <label for="title">Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                            placeholder="Enter title"
                            value="{{ old('title') }}">
                        @error('title')
                            <small class="text-red-500 px-3">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content">Content</label>
                        <textarea 
                            type="text" 
                            name="content" 
                            id="content" 
                            class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1"
                            rows="15"
                            placeholder="Enter title">{{ old('content') }}</textarea>
                        @error('content')
                            <small class="text-red-500 px-3">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="w-1/4">
                    <div class="mb-3">
                        <label 
                            for="thumbnail" 
                            class="cursor-pointer flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Select Thumbnial
                        </label>
                        <input id="thumbnail" type="file" class="hidden" name="thumbnail"/>
                        @error('thumbnail')
                            <small class="text-red-500 px-3">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="categories">Categories</label>
                        <select 
                            name="categories[]" 
                            id="categories" 
                            class="multiple-select w-full mt-2 mb-1"
                            multiple>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('categories') and in_array($category->id, old('categories')))? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categories')
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

                    <div class="mb-3">
                        <button type="submit" class="w-full bg-green-700 text-white rounded-md p-2 cursor-pointer">Save Post</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection