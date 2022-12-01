<?php

namespace App\Controllers\User;

use Inertia\Response;
use App\Models\Banner;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Banner\StoreAction;
use App\Actions\Banner\DeleteAction;
use App\Actions\Banner\UpdateAction;
use App\Requests\Banner\StoreRequest;
use Illuminate\Http\RedirectResponse;
use App\Requests\Banner\DeleteRequest;
use App\Requests\Banner\UpdateRequest;

class BannerController extends Controller
{
    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Delete the given banner.
     *
     */
    public function delete(DeleteRequest $request, Banner $banner) : RedirectResponse
    {
        DeleteAction::execute($banner);

        return redirect()
            ->route('banners')
            ->notify('The banner has been deleted');
    }

    /**
     * Show the banners page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Banners')
            ->view('banners.index')
            ->with('banners', user()->banners)
            ->with('limit', config('system.banner_limit'));
    }

    /**
     * Store a new banner.
     *
     */
    public function store(StoreRequest $request) : RedirectResponse
    {
        StoreAction::execute(user(), $request->validated());

        return redirect()
            ->route('banners')
            ->notify('The banner has been created');
    }

    /**
     * Update the given banner.
     *
     */
    public function update(UpdateRequest $request, Banner $banner) : RedirectResponse
    {
        UpdateAction::execute($banner, $request->validated());

        return redirect()
            ->route('banners')
            ->notify('The banner has been updated');
    }
}
