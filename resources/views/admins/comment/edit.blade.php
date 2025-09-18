@extends('layouts.admin', ['title' => 'Edit Comment ' . $comment->name])

@section('main')
<main class="flex-1 p-6">
    <h2 class="text-3xl font-semibold mb-4">Edit Comment {{ $comment->name }}</h2>
    <div class="bg-white p-6 rounded shadow-sm">
        <form action="{{ route('comment.update', ['comment' => $comment->id]) }}" enctype="multipart/form-data" method="POST">
            @method('PUT')
            @csrf
            <div class="grid grid-cols-3 justify-between gap-3">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter name"
                        value="{{ $comment->name }}">
                    @error('name')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1" 
                        placeholder="Enter email"
                        value="{{ $comment->email }}">
                    @error('email')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status">Status</label>
                    <select 
                        name="status" 
                        id="status" 
                        class="multiple-select w-full mt-2 mb-1 p-2">
                        @foreach (App\Enums\CommentStatuEnum::cases() as $item)
                            <option value="{{ $item->value }}" {{ $item == $comment->status ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <small class="text-red-500 px-3">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="comment">Comment</label>
                <textarea 
                    type="text" 
                    name="comment" 
                    id="comment" 
                    class="w-full bg-gray-100 rounded-md p-2 mt-2 mb-1"
                    rows="15"
                    placeholder="Enter title">{{ $comment->comment }}</textarea>
                @error('comment')
                    <small class="text-red-500 px-3">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <button type="submit" class="bg-green-700 text-white rounded-md p-2 cursor-pointer">Update Comment</button>
            </div>
        </form>
    </div>
</main>
@endsection