<?php

namespace App\Controllers\User;

use App\Types\Controller;
use App\Actions\User\UpdateAction;
use Illuminate\Http\RedirectResponse;
use App\Requests\Settings\UpdateRequest;

class SettingsController extends Controller
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
     * Update the current user's settings.
     *
     */
    public function update(UpdateRequest $request) : RedirectResponse
    {
        $settings = array_merge(user()->settings, $request->validated());

        UpdateAction::execute(user(), compact('settings'));

        return redirect()
            ->route('account')
            ->notify('Your account has been updated');
    }
}
