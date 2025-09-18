<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View as FacadesView;
use Illuminate\View\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FacadesView::composer(['templates.blog', 'templates.category', 'templates.search'], function (View $view) {

            $view->with('popularCategories', $this->getPopularCategories());

        });
    }

    private function getPopularCategories()
    {
        return  Category::withCount([
                'posts' => fn($query) => $query->where('status', true)
            ])
            ->orderBy('posts_count', 'Desc')
            ->take(5)
            ->get();
    }
}
