<?php

namespace App\Http\Controllers\api;

use App\Actoins\Template\HomePageAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TemplateController extends Controller
{
    public function home(HomePageAction $action)
    {
        $result = $action->handle();

        return Response::json([$result]);
    }
}
