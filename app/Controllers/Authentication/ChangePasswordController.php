<?php

namespace App\Controllers\Authentication;

use App\Types\Controller;
use App\Actions\User\UpdateAction;
use Illuminate\Http\RedirectResponse;
use App\Requests\Password\ChangeRequest;

class ChangePasswordController extends Controller
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
     * Change the user's password.
     *
     */
    public function update(ChangeRequest $request) : RedirectResponse
    {
        UpdateAction::execute(user(), ['password' => $request->new_password]);

        return redirect()
            ->route('account')
            ->notify('Your password has been updated');
    }
}
