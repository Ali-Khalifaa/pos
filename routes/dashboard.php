<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ClientController;
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
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){
        
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function (){


            Route::get('index',[DashboardController::class,'index'])->name('index');

            // categories routes
            Route::resource('categories', 'CategoryController')->except(['show']);

            // product routes
            Route::resource('products', 'ProductController')->except(['show']);

            // client routes
            Route::resource('clients', 'ClientController')->except(['show']);

            // user routes
            Route::resource('users', 'UserController')->except(['show']);

            

        });
            
        
    });
    



