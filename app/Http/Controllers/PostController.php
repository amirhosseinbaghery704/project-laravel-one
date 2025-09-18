<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Post::class);

        /** @var User */
        $user = Auth::user();

        $posts = Post::with('categories', 'user')
            ->where(function ($query) use ($request) {
                return $query->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            })
            ->when($request->has('trash'), fn($query) => $query->onlyTrashed())
            // ->when($user->isUser(), fn($query) => $query->where('user_id', $user->id))
            ->withCount('comments')
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return View::make('admins.post.index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Post::class);

        $categories = Category::all(['id', 'name']);

        return View::make('admins.post.create', [
            'categories' => $categories
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        // Post::create([
        //     'title' => $data['title'],
        //     'content' => $data['content'],
        //     'status' => $data['status'] == 'active' ? true : false,
        //     'user_id' => Auth::id()
        // ]);

        $slug = Str::slug($data['title']);
        if (Post::where('slug', $slug)->count())
            $slug = $slug . "-" . uniqid();

        /** @var User  */
        $user = Auth::user();

        $thumbnail = $data['thumbnail']->store();

        $post = $user->posts()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'status' => $data['status'] == 'active' ? true : false,
            'thumbnail' => "storage/{$thumbnail}"
        ]);

        $post->categories()->attach($data['categories']);

        return redirect()->route('post.index')
            ->with('message', "post `{$post->title}` has been created.");
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        $post->load('categories');

        $categories = Category::all(['id', 'name']);

        return View::make('admins.post.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $data = $request->validated();

        // $post->title = $data['title'];
        // $post->content = $data['content'];
        // $post->status = $data['status'];

        // if ($post->isDirty()) {
        //     $post->save();

        //     return redirect()->route('post.index')
        //         ->with('message', "post `{$post->title}` has been updated.");
        // }

        $thumbnail = $post->thumbnail;
        if ($data['thumbnail']) {
            $thumbnail = 'storage/' . $data['thumbnail']->store();
            Storage::disk('public')->delete( Str::of($post->thumbnail)->replace('storage/', ''));
        }

        $slug = Str::slug($data['title']);
        if (Post::where('slug', $slug)->where('id', '<>', $post->id)->count())
            $slug = $slug . "-" . uniqid();

        $post->update([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'status' => $data['status'] == 'active' ? true : false,
            'thumbnail' => $thumbnail
        ]);

        $post->categories()->sync($data['categories']);

        return redirect()->route('post.index')
            ->with('message', "post `{$post->title}` has been updated.");
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->back()->with('message', "post `{$post->title}` has been deleted.");
    }

    public function change(Post $post)
    {
        Gate::authorize('change', $post);

        $post->status = !$post->status;
        $post->save();

        return redirect()->back()->with('message', "post `{$post->title}` has been changed.");
    }

    public function restore(int $id)
    {
        $post = Post::onlyTrashed()->where('id', $id)
            ->firstOrFail();

        Gate::authorize('restore', $post);


        $post->restore();

        return redirect()->back()->with('message', "post `{$post->title}` has been restored.");
    }

    public function forceDelete(int $id)
    {
        $post = Post::onlyTrashed()->where('id', $id)
            ->firstOrFail();

        Gate::authorize('forceDelete', $post);

        $post->forceDelete();

        return redirect()->back()->with('message', "post `{$post->title}` has been force deleted.");
    }
}
