<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/admin')->group(function(){
    //admin login route
    Route::match(['get', 'post'], '/login', 'AdminLoginController@adminLogin')->name('adminLogin');

        Route::group(['middleware' => ['admin']], function(){
            //admin dashboard route
            Route::get('/dashboard', 'AdminLoginController@dashboard')->name('adminDashboard');

            //admin profile Route
            Route::get('/profile', 'AdminProfileController@profile')->name('profile');

            //admin profile update
             Route::post('/profile/update/{id}', 'AdminProfileController@updateProfile')->name('updateProfile');

             //change password
            Route::get('/profile/change_password', 'AdminProfileController@changePassword')->name('changePassword');

            //check current password
            Route::post('/profile/check-password', 'AdminProfileController@chkUserPassword')->name('chkUserPassword');

            //update admin password
            Route::post('/profile/update_password/{id}', 'AdminProfileController@updatePassword')->name('updatePassword');
    });
});

Route::get('/admin/logout', 'AdminLoginController@adminLogout')->name('adminLogout');
