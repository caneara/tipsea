<?php

namespace App\Controllers\User;

use App\Models\Tip;
use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use Illuminate\Http\JsonResponse;
use App\Actions\Bookmark\ViewAction;
use App\Actions\Bookmark\StoreAction;
use App\Actions\Bookmark\DeleteAction;
use App\Requests\Bookmark\IndexRequest;
use App\Requests\Bookmark\StoreRequest;
use App\Requests\Bookmark\DeleteRequest;

class BookmarkController extends Controller
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
     * Delete the given bookmark.
     *
     */
    public function delete(DeleteRequest $request, Tip $tip) : JsonResponse
    {
        DeleteAction::execute(user(), $tip);

        return response()->json('done');
    }

    /**
     * Show the bookmarks page.
     *
     */
    public function index(IndexRequest $request) : Response
    {
        return Page::make()
            ->title('Bookmarks')
            ->view('bookmarks.index')
            ->with('tips', fn() => ViewAction::execute(user(), $request->validated()));
    }

    /**
     * Store a new bookmark.
     *
     */
    public function store(StoreRequest $request, Tip $tip) : JsonResponse
    {
        StoreAction::execute(user(), $tip);

        return response()->json('done');
    }
}
