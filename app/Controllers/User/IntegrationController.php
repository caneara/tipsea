<?php

namespace App\Controllers\User;

use App\Types\Controller;
use App\Services\Twitter\Auth;
use App\Actions\User\UpdateAction;
use Illuminate\Http\RedirectResponse;
use App\Requests\Integration\StoreRequest;
use App\Requests\Integration\CreateRequest;
use App\Requests\Integration\DeleteRequest;

class IntegrationController extends Controller
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
     * Create a new integration.
     *
     */
    public function create(CreateRequest $request) : RedirectResponse
    {
        Auth::obtainRequestToken(user());

        $url = Auth::getAuthorizationUrl(user());

        return redirect($url);
    }

    /**
     * Delete the existing integration.
     *
     */
    public function delete(DeleteRequest $request) : RedirectResponse
    {
        UpdateAction::execute(user(), ['integration' => []]);

        return redirect()
            ->route('account')
            ->notify('You are now disconnected');
    }

    /**
     * Store a new integration.
     *
     */
    public function store(StoreRequest $request) : RedirectResponse
    {
        $payload = Auth::obtainAccessToken($request->validated());

        UpdateAction::execute(user(), ['integration' => $payload]);

        return redirect()
            ->route('account')
            ->notify('You are now connected');
    }
}
