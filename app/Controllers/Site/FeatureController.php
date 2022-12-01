<?php

namespace App\Controllers\Site;

use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;

class FeatureController extends Controller
{
    /**
     * Show the features page.
     *
     */
    public function index() : Response
    {
        return Page::make()
            ->title('Features')
            ->view('features.index')
            ->render();
    }
}
