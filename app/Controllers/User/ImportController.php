<?php

namespace App\Controllers\User;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use Illuminate\Http\JsonResponse;
use App\Actions\Import\StoreAction;
use App\Requests\Import\StoreRequest;
use Illuminate\Http\RedirectResponse;
use App\Requests\Import\VerifyRequest;
use Illuminate\Http\Response as HttpResponse;

class ImportController extends Controller
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
     * Show the import page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Import')
            ->view('import.index')
            ->with('banners', user()->banners);
    }

    /**
     * Store a new import.
     *
     */
    public function store(StoreRequest $request) : RedirectResponse
    {
        StoreAction::execute(user(), $request->validated());

        return redirect()
            ->route('tips')
            ->notify('The tips have been imported');
    }

    /**
     * Verify that an import is valid.
     *
     */
    public function verify(VerifyRequest $request) : JsonResponse
    {
        return response()->json('valid', HttpResponse::HTTP_ACCEPTED);
    }
}
