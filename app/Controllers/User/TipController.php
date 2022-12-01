<?php

namespace App\Controllers\User;

use App\Models\Tip;
use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Tip\ViewAction;
use App\Actions\Tip\StoreAction;
use App\Actions\Tip\DeleteAction;
use App\Actions\Tip\UpdateAction;
use App\Requests\Tip\EditRequest;
use App\Requests\Tip\IndexRequest;
use App\Requests\Tip\StoreRequest;
use App\Requests\Tip\DeleteRequest;
use App\Requests\Tip\UpdateRequest;
use Illuminate\Http\RedirectResponse;

class TipController extends Controller
{
    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('throttle:5,10')->only('store');
    }

    /**
     * Create a new tip.
     *
     */
    public function create() : Response
    {
        return Page::make()
            ->title('Create Tip')
            ->view('tips.create.index')
            ->with('banners', user()->banners);
    }

    /**
     * Delete the given tip.
     *
     */
    public function delete(DeleteRequest $request, Tip $tip) : RedirectResponse
    {
        DeleteAction::execute($tip);

        return redirect()
            ->route('tips')
            ->notify('The tip has been deleted');
    }

    /**
     * Delete the given tip.
     *
     */
    public function edit(EditRequest $request, Tip $tip) : Response
    {
        return Page::make()
            ->title('Edit Tip')
            ->view('tips.edit.index')
            ->with('tip', $tip)
            ->with('banners', user()->banners)
            ->with('published', $tip->published_at && $tip->published_at->startOfMinute()->isPast());
    }

    /**
     * Show the tips page.
     *
     */
    public function index(IndexRequest $request) : Response
    {
        return Page::make()
            ->title('Tips')
            ->view('tips.view.index')
            ->with('tips', fn() => ViewAction::execute(user(), $request->validated()));
    }

    /**
     * Store a new tip.
     *
     */
    public function store(StoreRequest $request) : RedirectResponse
    {
        $tip = StoreAction::execute(user(), $request->validated());

        return redirect()
            ->route('tips.edit', ['tip' => $tip])
            ->notify('The tip has been created');
    }

    /**
     * Update the given tip.
     *
     */
    public function update(UpdateRequest $request, Tip $tip) : RedirectResponse
    {
        UpdateAction::execute($tip, $request->validated());

        return redirect()
            ->route('tips.edit', ['tip' => $tip])
            ->notify('The tip has been updated');
    }
}
