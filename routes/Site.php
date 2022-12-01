<?php

use Illuminate\Support\Facades\Route;

/**
 * Home routes.
 *
 */
Route::get('/', 'HomeController@index')->name('home');

/**
 * Legal routes.
 *
 */
Route::get('/legal', 'LegalController@index')->name('legal');

/**
 * Feature routes.
 *
 */
Route::get('/features', 'FeatureController@index')->name('features');

/**
 * Profile routes.
 *
 */
Route::get('/@{user:handle}', 'ProfileController@show')->name('profile');

/**
 * Tip routes.
 *
 */
Route::get('/tips/show/{tip:slug}', 'TipController@show')->name('tips.show');
