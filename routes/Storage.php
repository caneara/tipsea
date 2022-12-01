<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Create a new signed storage url.
 *
 */
Route::post('/vapor/signed-storage-url', function() {
    Gate::authorize('uploadFiles', [user()]);

    return response()->json([
        'uuid'    => $id = uuid(),
        'headers' => compact('id'),
        'url'     => URL::temporarySignedRoute('signed.storage.upload', now()->addHour(), compact('id')),
    ]);
})->name('signed.storage.url');

/**
 * Move a signed file to a temporary location.
 *
 */
Route::middleware('signed')->put('/vapor/signed-storage-upload/{id}', function() {
    Gate::authorize('uploadFiles', [user()]);

    Storage::put('tmp/' . request()->header('id'), request()->getContent());

    return response()->json('The file was uploaded');
})->name('signed.storage.upload');
