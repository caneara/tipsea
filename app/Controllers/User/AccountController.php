<?php

namespace App\Controllers\User;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\User\DeleteAction;
use App\Actions\User\UpdateAction;
use Illuminate\Http\RedirectResponse;
use App\Requests\Account\DeleteRequest;
use App\Requests\Account\UpdateRequest;

class AccountController extends Controller
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
     * Delete the current user.
     *
     */
    public function delete(DeleteRequest $request) : RedirectResponse
    {
        DeleteAction::execute(user());

        return redirect()
            ->route('home')
            ->notify('Your account has been deleted');
    }

    /**
     * Show the account page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Account')
            ->view('account.index')
            ->with('user', user())
            ->with('settings', user()->settings)
            ->with('integration', filled(user()->integration));
    }

    /**
     * Update the current user.
     *
     */
    public function update(UpdateRequest $request) : RedirectResponse
    {
        UpdateAction::execute(user(), $request->validated());

        return redirect()
            ->route('account')
            ->notify('Your account has been updated');
    }
}
