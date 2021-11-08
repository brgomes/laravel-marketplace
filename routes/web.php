<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/product/{slug}', 'HomeController@single')->name('product.single');

Route::group(['middleware' => 'auth'], function () {
    Route::prefix('admin')->name('admin.')->namespace('Admin')->group(function () {
        /*Route::prefix('stores')->name('stores.')->group(function () {
            Route::get('/', 'StoreController@index')->name('index');
            Route::get('/create', 'StoreController@create')->name('create');
            Route::post('/store', 'StoreController@store')->name('store');
            Route::get('/{store}/edit', 'StoreController@edit')->name('edit');
            Route::post('/{store}/update', 'StoreController@update')->name('update');
            Route::get('/{store}/destroy', 'StoreController@destroy')->name('destroy');
        });*/
    
        Route::resource('stores', 'StoreController');
        Route::resource('products', 'ProductsController');
        Route::resource('categories', 'CategoriesController');

        Route::post('/photos/{photo}/remove', 'ProductPhotoController@removePhoto')
            ->name('photo.remove');
    });
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
