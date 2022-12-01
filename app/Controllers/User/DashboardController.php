<?php

namespace App\Controllers\User;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Dashboard\ViewAction;

class DashboardController extends Controller
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
     * Show the dashboard page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Dashboard')
            ->view('dashboard.index')
            ->with('tips', fn() => ViewAction::execute(user()));
    }
}
