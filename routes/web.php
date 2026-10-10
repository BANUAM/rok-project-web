
<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/teams', 'pages.teams')->name('teams');
Route::view('/achievements', 'pages.achievements')->name('achievements');
Route::view('/trial', 'pages.trial')->name('trial');
Route::view('/learning-development', 'pages.learning-development')
    ->name('learning-development');
Route::view('/bracket', 'pages.bracket')->name('bracket');
