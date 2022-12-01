<?php

namespace App\Controllers\Site;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Home\ViewAction;
use App\Requests\Home\IndexRequest;

class HomeController extends Controller
{
    /**
     * Show the home page.
     *
     */
    public function index(IndexRequest $request) : Response
    {
        return Page::make()
            ->view('home.index')
            ->with('tips', fn() => ViewAction::execute(user(), $request->validated()));
    }
}
