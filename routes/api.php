<?php


use App\Http\Controllers\api\TemplateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('home', [TemplateController::class, 'home'])->name('api');
