<?php

namespace App\Policies;

use App\Models\User;
use App\Types\Policy;
use App\Models\Banner;

class BannerPolicy extends Policy
{
    /**
     * Determine whether the given user can delete the given banner.
     *
     */
    public function delete(User $user, Banner $banner) : bool
    {
        return $user->owns($banner);
    }

    /**
     * Determine whether the given user can store banners.
     *
     */
    public function store(User $user) : bool
    {
        return $user->banners()->count() < config('system.banner_limit');
    }

    /**
     * Determine whether the given user can update the given banner.
     *
     */
    public function update(User $user, Banner $banner) : bool
    {
        return $user->owns($banner);
    }
}
