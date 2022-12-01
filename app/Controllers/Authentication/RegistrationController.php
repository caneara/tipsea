<?php

namespace App\Controllers\Authentication;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\User\StoreAction;
use App\Actions\Email\NotifyAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Requests\Register\StoreRequest;

class RegistrationController extends Controller
{
    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:5,10')->only('store');
    }

    /**
     * Show the registration page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Register')
            ->view('register.index')
            ->render();
    }

    /**
     * Register a new account.
     *
     */
    public function store(StoreRequest $request) : RedirectResponse
    {
        $user = StoreAction::execute($request->validated());

        Auth::login($user);

        NotifyAction::execute($user);

        return redirect()->route('email.verify.notice');
    }
}
