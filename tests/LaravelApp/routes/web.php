<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('site1.test')->name('site1.test.')->group(function ($router) {
    $router->get('/', [DomainController::class, 'index'])->name('index');
    $router->get('/login', [DomainController::class, 'index'])->name('login');
    $router->get('/signup', [DomainController::class, 'index'])->name('signup');
    $router->get('/other', [DomainController::class, 'index'])->name('other');
});

Route::domain('site2.test')->name('site2.test.')->group(function ($router) {
    $router->get('/', [DomainController::class, 'index'])->name('index');
    $router->get('/manual', [DomainController::class, 'index'])->name('manual');
    $router->get('/downloads', [DomainController::class, 'index'])->name('downloads');
});

Route::domain('other-domain.test')->group(function () {
    Route::get('/', [DomainController::class, 'index']);
});

Route::domain('domain-not-found.test')->group(function () {
    Route::get('/', [DomainController::class, 'index']);
});
