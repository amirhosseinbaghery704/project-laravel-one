<?php

namespace App\Http\Controllers;

use App\Actoins\Template\HomePageAction;
use App\Enums\CommentStatuEnum;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
    public function home(HomePageAction $action)
    {
        $result = $action->handle();

        return View::make('templates.home', $result);
    }

    public function blog(Request $request)
    {
        $order = ($request->filled('order') and in_array($request->order, ['asc', 'desc']))? $request->order : 'desc';

        $posts = Post::where('status', true)
            ->with('categories', 'user')
            ->orderBy('created_at', $order)
            ->paginate(9)
            ->withQueryString();

        return View::make('templates.blog', [
            'posts' => $posts,
        ]);
    }

    public function category(Category $category, Request $request)
    {
        $order = ($request->filled('order') and in_array($request->order, ['asc', 'desc']))? $request->order : 'desc';

        // $posts = Post::whereHas('categories', fn($query) => $query->where('id', $id))
        //      ->where('status', true)
        //     ->orderBy('created_at', 'DESC')
        //     ->paginate(9);

        $posts = $category->posts()
            ->where('status', true)
            ->orderBy('created_at', $order)
            ->paginate(9);

        return View::make('templates.category', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function search(Request $request)
    {
        if (!$request->filled('word'))
            return Redirect::route('home');

        $order = ($request->filled('order') and in_array($request->order, ['asc', 'desc']))? $request->order : 'desc';
            
        $word = $request->word;
        $posts = Post::where(function (Builder $query) use ($word) {
                return $query->where('title', 'like', "%{$word}%")
                    ->orWhere('content', 'like', "%{$word}%");
            })
            ->where('status', true)
            ->orderBy('created_at', $order)
            ->paginate(9)
            ->withQueryString();


        return View::make('templates.search', [
            'posts' => $posts
        ]);
    }

    public function single(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', true)
            ->with([
                'user', 
                'categories', 
                'comments' => fn($query) => $query->where('status', CommentStatuEnum::ACCEPT)
            ])->firstOrFail();

        return View::make('templates.single', [
            'post' => $post
        ]);
    }

    public function comment(int $id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => ['required', 'string', 'email', 'max:100'],
            'comment' => ['required', 'string', 'min:5', 'max:10000'],
            'captcha' => 'required|captcha'
        ]);

        $throttleKey = $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 2)) {
            throw ValidationException::withMessages([
                'name' => [__('Too many login attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($throttleKey)
                ])],
            ]);
        }

        RateLimiter::hit($throttleKey, 60); // count failed attempt

        $post = Post::where('status', true)
            ->where('id', $id)
            ->firstOrFail();

        $data['status'] = CommentStatuEnum::PENDING;
        $comment = $post->comments()->create($data);

        $user = User::find(1);
        Notification::send($user, new CommentNotification($comment));

        return Redirect::back()->with('message', "Comment was posted and show after accept by admin.")->withFragment('comment');
    }
}
