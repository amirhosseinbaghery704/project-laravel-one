<?php
namespace App\Actoins\Template;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\ErrorHandler\Collecting;

class HomePageAction{
    public function handle(){
        return [
            'posts'=>$this->getlastposts(),
            'randomPosts'=>$this->getrandomposts(),
        ];
    }
        private function getlastposts(){
            return  Post::where('status', true)
                ->with('categories', 'user')
                ->orderBy('created_at', 'DESC')
                ->take(9)
                ->get();
        }
        private function getrandomposts(){
            return  Post::InRandomOrder()
                ->with('user', 'categories')
                ->where('status', true)
                ->orderBy('created_at', 'DESC')
                ->take(3)
                ->get(); 
        }
}
 