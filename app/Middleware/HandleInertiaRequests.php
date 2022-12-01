<?php

namespace App\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template.
     *
     */
    protected $rootView = 'app.index';

    /**
     * Define the properties that are shared by default.
     *
     */
    public function share(Request $request) : array
    {
        return array_merge(parent::share($request), [
            'csrf'         => csrf_token(),
            'asset'        => asset(''),
            'version'      => $this->version($request),
            'guest'        => Auth::guest(),
            'storage'      => Storage::url(''),
            'user'         => user()?->only(['id', 'type', 'name', 'handle', 'avatar', 'metrics']) ?? [],
            'notification' => fn() => rescue(fn() => session('notification'), null, false),
        ]);
    }
}
