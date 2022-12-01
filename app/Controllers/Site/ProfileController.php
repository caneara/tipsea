<?php

namespace App\Controllers\Site;

use App\Models\User;
use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Profile\ShowAction;
use App\Requests\Profile\ShowRequest;

class ProfileController extends Controller
{
    /**
     * Show the given user's profile page.
     *
     */
    public function show(ShowRequest $request, User $user) : Response
    {
        return Page::make()
            ->meta($user)
            ->title($user->name)
            ->view('profile.index')
            ->with('profile', $user->only($request->fields))
            ->with('tips', fn() => ShowAction::execute($user))
            ->with('follower', ShowAction::follower(user(), $user));
    }
}
