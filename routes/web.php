<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('admin-users')->name('admin-users/')->group(static function() {
            Route::get('/',                                             'AdminUsersController@index')->name('index');
            Route::get('/create',                                       'AdminUsersController@create')->name('create');
            Route::post('/',                                            'AdminUsersController@store')->name('store');
            Route::get('/{adminUser}/impersonal-login',                 'AdminUsersController@impersonalLogin')->name('impersonal-login');
            Route::get('/{adminUser}/edit',                             'AdminUsersController@edit')->name('edit');
            Route::post('/{adminUser}',                                 'AdminUsersController@update')->name('update');
            Route::delete('/{adminUser}',                               'AdminUsersController@destroy')->name('destroy');
            Route::get('/{adminUser}/resend-activation',                'AdminUsersController@resendActivationEmail')->name('resendActivationEmail');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::get('/profile',                                      'ProfileController@editProfile')->name('edit-profile');
        Route::post('/profile',                                     'ProfileController@updateProfile')->name('update-profile');
        Route::get('/password',                                     'ProfileController@editPassword')->name('edit-password');
        Route::post('/password',                                    'ProfileController@updatePassword')->name('update-password');
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('anchors')->name('anchors/')->group(static function() {
            Route::get('/',                                             'AnchorsController@index')->name('index');
            Route::get('/create',                                       'AnchorsController@create')->name('create');
            Route::post('/',                                            'AnchorsController@store')->name('store');
            Route::get('/{anchor}/edit',                                'AnchorsController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'AnchorsController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{anchor}',                                    'AnchorsController@update')->name('update');
            Route::delete('/{anchor}',                                  'AnchorsController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('subcribes')->name('subcribes/')->group(static function() {
            Route::get('/',                                             'SubcribesController@index')->name('index');
            Route::get('/create',                                       'SubcribesController@create')->name('create');
            Route::post('/',                                            'SubcribesController@store')->name('store');
            Route::get('/{subcribe}/edit',                              'SubcribesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'SubcribesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{subcribe}',                                  'SubcribesController@update')->name('update');
            Route::delete('/{subcribe}',                                'SubcribesController@destroy')->name('destroy');
        });
    });
});

/* Auto-generated admin routes */
Route::middleware(['auth:' . config('admin-auth.defaults.guard'), 'admin'])->group(static function () {
    Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->name('admin/')->group(static function() {
        Route::prefix('subscribes')->name('subscribes/')->group(static function() {
            Route::get('/',                                             'SubscribesController@index')->name('index');
            Route::get('/create',                                       'SubscribesController@create')->name('create');
            Route::post('/',                                            'SubscribesController@store')->name('store');
            Route::get('/{subscribe}/edit',                             'SubscribesController@edit')->name('edit');
            Route::post('/bulk-destroy',                                'SubscribesController@bulkDestroy')->name('bulk-destroy');
            Route::post('/{subscribe}',                                 'SubscribesController@update')->name('update');
            Route::delete('/{subscribe}',                               'SubscribesController@destroy')->name('destroy');
        });
    });
});

Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/register', [AdminAuthController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::get('/confirm/{id}', [AdminAuthController::class, 'confirm']);
});
