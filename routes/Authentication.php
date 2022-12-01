<?php

use Illuminate\Support\Facades\Route;

/**
 * Registration routes.
 *
 */
Route::get('/register', 'RegistrationController@index')->name('register');
Route::post('/register', 'RegistrationController@store')->name('register.store');

/**
 * Authentication routes.
 *
 */
Route::get('/login', 'LoginController@index')->name('login');
Route::get('/logout', 'LogoutController@index')->name('logout');
Route::post('/login', 'LoginController@store')->name('login.store');

/**
 * Email verification routes.
 *
 */
Route::post('/email/send', 'VerifyEmailController@store')->name('email.verify.send');
Route::get('/email/notice', 'VerifyEmailController@index')->name('email.verify.notice');
Route::get('/email/{id}/{hash}', 'VerifyEmailController@update')->name('email.verify.confirm');

/**
 * Password routes.
 *
 */
Route::patch('/password', 'ChangePasswordController@update')->name('password.update');
Route::get('/password/forgot', 'ForgotPasswordController@index')->name('password.forgot');
Route::post('/password/forgot', 'ForgotPasswordController@store')->name('password.forgot.store');
Route::post('/password/reset', 'ResetPasswordController@store')->name('password.reset.store');
Route::get('/password/reset/{token}', 'ResetPasswordController@index')->name('password.reset');
