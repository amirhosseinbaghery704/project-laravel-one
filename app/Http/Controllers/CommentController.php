<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatuEnum;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function __construct()
    {
        Gate::authorize('admin');
    }

    public function index(Request $request)
    {
        $comments = Comment::orderBy('created_at', 'DESC')
            ->where('name', 'LIKE', "%{$request->search}%")
            ->with('post')
            ->paginate(10);

        return View::make('admins.comment.index', [
            'comments' => $comments
        ]);
    }

    public function edit(Comment $comment)
    {
        $comment->load('post');

        return View::make('admins.comment.edit', [
            'comment' => $comment
        ]);
    }

    public function update(Comment $comment, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => ['required', 'string', 'email', 'max:100'],
            'comment' => ['required', 'string', 'min:5', 'max:10000'],
            'status' => ['required', Rule::enum(CommentStatuEnum::class)]
        ]);

        $comment->update($data);

        return redirect()->route('comment.index')->with('message', "Comment `{$comment->name}` has been edited.");
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('message', "Comment `{$comment->name}` has been deleted.");
    }
}
