<?php

namespace App\Controllers\User;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Notification\ViewAction;

class NotificationController extends Controller
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
     * Show the notifications page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Notifications')
            ->view('notifications.index')
            ->with('notifications', fn() => ViewAction::execute(user()));
    }
}
