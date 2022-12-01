<?php

namespace App\Controllers\User;

use App\Models\User;
use App\Types\Controller;
use Illuminate\Http\JsonResponse;
use App\Actions\Follower\StoreAction;
use App\Actions\Follower\DeleteAction;
use App\Requests\Follower\StoreRequest;
use App\Requests\Follower\DeleteRequest;

class FollowerController extends Controller
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
     * Stop following the given teacher.
     *
     */
    public function delete(DeleteRequest $request, User $teacher) : JsonResponse
    {
        DeleteAction::execute(user(), $teacher);

        return response()->json('done');
    }

    /**
     * Start following the given teacher.
     *
     */
    public function store(StoreRequest $request, User $teacher) : JsonResponse
    {
        StoreAction::execute(user(), $teacher);

        return response()->json('done');
    }
}
