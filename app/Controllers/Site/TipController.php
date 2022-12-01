<?php

namespace App\Controllers\Site;

use App\Models\Tip;
use Inertia\Response;
use App\Responses\Page;
use App\Types\Controller;
use App\Actions\Tip\ShowAction;
use App\Requests\Tip\ShowRequest;

class TipController extends Controller
{
    /**
     * Show the given tip.
     *
     */
    public function show(ShowRequest $request, Tip $tip) : Response
    {
        $page = $request->get('no_embed', false)
            ? Page::make()->withoutMeta()
            : Page::make()->meta($tip);

        return $page->title($tip->title)
            ->view('tips.show.index')
            ->with('tip', $tip = ShowAction::execute($tip))
            ->with('related', ShowAction::related(user(), $tip))
            ->with('liked', ShowAction::liked(user(), $tip['id']))
            ->with('comments', fn() => ShowAction::comments($tip['id']))
            ->with('follower', ShowAction::follower(user(), $tip['user']))
            ->with('bookmarked', ShowAction::bookmarked(user(), $tip['id']));
    }
}
