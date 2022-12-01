<?php

use Illuminate\Support\Facades\Route;

/**
 * Dashboard routes.
 *
 */
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

/**
 * Account routes.
 *
 */
Route::get('/account', 'AccountController@index')->name('account');
Route::patch('/account', 'AccountController@update')->name('account.update');
Route::delete('/account', 'AccountController@delete')->name('account.delete');

/**
 * Avatar routes.
 *
 */
Route::patch('/avatar', 'AvatarController@update')->name('avatar.update');

/**
 * Settings routes.
 *
 */
Route::patch('/settings', 'SettingsController@update')->name('settings.update');

/**
 * Notification routes.
 *
 */
Route::get('/notifications', 'NotificationController@index')->name('notifications');

/**
 * Banner routes.
 *
 */
Route::get('/banners', 'BannerController@index')->name('banners');
Route::post('/banners', 'BannerController@store')->name('banners.store');
Route::patch('/banners/{banner}', 'BannerController@update')->name('banners.update');
Route::delete('/banners/{banner}', 'BannerController@delete')->name('banners.delete');

/**
 * Bookmark routes.
 *
 */
Route::get('/bookmarks', 'BookmarkController@index')->name('bookmarks');
Route::post('/bookmarks/{tip}', 'BookmarkController@store')->name('bookmarks.store');
Route::delete('/bookmarks/{tip}', 'BookmarkController@delete')->name('bookmarks.delete');

/**
 * Follower routes.
 *
 */
Route::post('/follower/{teacher}', 'FollowerController@store')->name('follower.store');
Route::delete('/follower/{teacher}', 'FollowerController@delete')->name('follower.delete');

/**
 * Integration routes.
 *
 */
Route::get('/integration/store', 'IntegrationController@store')->name('integration.store');
Route::get('/integration/create', 'IntegrationController@create')->name('integration.create');
Route::delete('/integration/delete', 'IntegrationController@delete')->name('integration.delete');

/**
 * Tip routes.
 *
 */
Route::get('/tips', 'TipController@index')->name('tips');
Route::post('/tips', 'TipController@store')->name('tips.store');
Route::get('/tips/create', 'TipController@create')->name('tips.create');
Route::get('/tips/{tip}/edit', 'TipController@edit')->name('tips.edit');
Route::patch('/tips/{tip}', 'TipController@update')->name('tips.update');
Route::delete('/tips/{tip}', 'TipController@delete')->name('tips.delete');

/**
 * Import routes.
 *
 */
Route::get('/imports', 'ImportController@index')->name('imports');
Route::post('/imports', 'ImportController@store')->name('imports.store');
Route::post('/imports/verify', 'ImportController@verify')->name('imports.verify');

/**
 * Like routes.
 *
 */
Route::post('/likes/{tip}', 'LikeController@store')->name('likes.store');
Route::delete('/likes/{tip}', 'LikeController@delete')->name('likes.delete');

/**
 * Comment routes.
 *
 */
Route::post('/comments/{tip}', 'CommentController@store')->name('comments.store');
Route::patch('/comments/{comment}', 'CommentController@update')->name('comments.update');
Route::delete('/comments/{comment}', 'CommentController@delete')->name('comments.delete');
Route::post('/comments/{comment}/reply', 'CommentController@reply')->name('comments.reply');
