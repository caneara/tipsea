<?php

namespace App\Controllers\User;

use App\Models\Tip;
use App\Types\Controller;
use App\Actions\Like\StoreAction;
use Illuminate\Http\JsonResponse;
use App\Actions\Like\DeleteAction;
use App\Requests\Like\StoreRequest;
use App\Requests\Like\DeleteRequest;

class LikeController extends Controller
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
     * Cease liking the given tip.
     *
     */
    public function delete(DeleteRequest $request, Tip $tip) : JsonResponse
    {
        DeleteAction::execute(user(), $tip);

        return response()->json('done');
    }

    /**
     * Like the given tip.
     *
     */
    public function store(StoreRequest $request, Tip $tip) : JsonResponse
    {
        StoreAction::execute(user(), $tip);

        return response()->json('done');
    }
}
