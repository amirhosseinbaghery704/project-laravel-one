<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Comment::truncate();
        DB::table('category_post')->truncate();
        Post::truncate();
        Category::truncate();
        User::truncate();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'status' => true,
            'role' => UserRoleEnum::ADMIN
        ]);

        User::factory(10)->create();
        Category::factory(20)->create();

        $allCategories = Category::all()->pluck('id')->toArray();
        Post::factory(50)->create()->each(function ($post) use($allCategories) {
            $cats = Arr::random($allCategories, random_int(2, 6));
            $post->categories()
                ->attach($cats);
        });
        
        Comment::factory(50)->create();
        Schema::enableForeignKeyConstraints();
    }
}
