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

use App\Notifications\StoreReceiveNewOrder;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('', 'HomeController@index')->name('home');
Route::get('product/{slug}', 'HomeController@single')->name('product.single');
Route::get('category/{slug}', 'CategoryController@index')->name('category.single');
Route::get('store/{slug}', 'StoreController@index')->name('store.single');

Route::get('not', function () {
    $user = User::find(8);
    //$user->notify(new StoreReceiveNewOrder());

    return $user->notifications;
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('', 'CartController@index')->name('index');
    Route::post('add', 'CartController@add')->name('add');
    Route::get('remove/{slug}', 'CartController@remove')->name('remove');
    Route::get('cancel', 'CartController@cancel')->name('cancel');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('', 'CheckoutController@index')->name('index');
    Route::post('proccess', 'CheckoutController@proccess')->name('proccess');
    Route::get('thanks', 'CheckoutController@thanks')->name('thanks');

    Route::post('notification', 'CheckoutController@notification')->name('notification');
});

Route::get('my-orders', 'UserOrderController@index')->name('user.orders')->middleware('auth');

Route::group(['middleware' => ['auth', 'access.control.store.admin']], function () {
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
        
        Route::get('orders/my', 'OrdersController@index')->name('orders.my');

        Route::get('notifications', 'NotificationController@notifications')->name('notification.index');
        Route::get('notifications/read-all', 'NotificationController@readAll')->name('notification.read-all');
        Route::get('notifications/{notification}/read', 'NotificationController@read')->name('notification.read');
    });
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
