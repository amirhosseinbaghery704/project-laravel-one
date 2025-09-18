<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetNewsCommand extends Command
{

    protected $signature = 'get:news {site}';

    protected $description = 'Get top news form tasnim website';

    public function handle()
    {
        $site = $this->input->getArgument('site');
        if ($site == 'tasnim')
            $this->tasnim();
        else
            $this->mehr();
    }

    public function tasnim()
    {
        $response = Http::get('https://www.tasnimnews.com/fa/rss/feed/0/8/0/%D8%A2%D8%AE%D8%B1%DB%8C%D9%86-%D8%AE%D8%A8%D8%B1%D9%87%D8%A7%DB%8C-%D8%B1%D9%88%D8%B2');
        if ($response->getStatusCode() == 200) {
            $name = now()->format('y-m-d-h-i');
            Storage::disk('public')->put('files/tasnimnews_' . $name . '.xml' , $response->getBody()->getContents());
            $this->alert("tasnim news was taked");
        } else {
            $this->error('can not take news');
        }
    }

    public function mehr()
    {
        $response = Http::get('https://www.mehrnews.com/rss');
        if ($response->getStatusCode() == 200) {
            $name = now()->format('y-m-d-h-i');
            Storage::disk('public')->put('files/mehrnews_' . $name . '.xml' , $response->getBody()->getContents());
            $this->alert("mehr news was taked");
        } else {
            $this->error('can not take news');
        }
    }
}
