<?php

namespace Database\Seeders;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\Model;
use App\Models\Banner;
use App\Enums\UserType;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\Follower;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Traits\Conditionable;

class DatabaseSeeder extends Seeder
{
    use Conditionable;

    /**
     * The maximum number of users to create.
     *
     */
    protected int $limit;

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->limit = env('DEVELOPMENT_SEED_USERS', 100);
    }

    /**
     * Seed the application database.
     *
     */
    public function run() : void
    {
        cache()->flush();

        Model::preventLazyLoading(false);

        File::deleteDirectory(storage_path('app'), true);

        Storage::makeDirectory('images');

        $this->unless(app()->isProduction(), fn() => $this->seed());
    }

    /**
     * Populate the database with fake data.
     *
     */
    public function seed() : void
    {
        $this->seedUsers();
        $this->seedBanners();
        $this->seedTips();
        $this->seedBookmarks();
        $this->seedLikes();
        $this->seedComments();
        $this->seedFollowers();
        $this->seedNotifications();
    }

    /**
     * Populate the database with fake banners.
     *
     */
    protected function seedBanners() : void
    {
        User::lazy()->each(function($user) {
            Banner::factory()
                ->count(3)
                ->belongsTo($user)
                ->create();
        });
    }

    /**
     * Populate the database with fake bookmarks.
     *
     */
    protected function seedBookmarks() : void
    {
        Tip::lazy()->each(function($tip) {
            Bookmark::factory()
                ->belongsTo($tip)
                ->create(['user_id' => rand(1, $this->limit)]);
        });
    }

    /**
     * Populate the database with fake comments.
     *
     */
    protected function seedComments() : void
    {
        Tip::lazy()->each(function($tip) {
            $comments = Comment::factory()
                ->count(3)
                ->sequence(fn() => [
                    'user_id' => rand(1, $this->limit),
                    'tip_id'  => $tip,
                ])
                ->create();

            $comments->random(2)->each(function($comment) use ($tip) {
                Comment::factory()->create([
                    'user_id'   => rand(1, $this->limit),
                    'tip_id'    => $tip,
                    'parent_id' => $comment,
                ]);
            });
        });
    }

    /**
     * Populate the database with fake followers.
     *
     */
    protected function seedFollowers() : void
    {
        User::lazy()->each(function($user) {
            Follower::factory()
                ->count(3)
                ->sequence(fn() => [
                    'teacher_id' => $user,
                    'student_id' => rand(1, $this->limit),
                ])
                ->create();
        });
    }

    /**
     * Populate the database with fake likes.
     *
     */
    protected function seedLikes() : void
    {
        Tip::lazy()->each(function($tip) {
            Like::factory()
                ->count(5)
                ->sequence(fn() => [
                    'user_id' => rand(1, $this->limit),
                    'tip_id'  => $tip,
                ])
                ->create();
        });
    }

    /**
     * Populate the database with fake notifications.
     *
     */
    protected function seedNotifications() : void
    {
        User::with('tips')->lazy()->each(function($user) {
            Notification::factory()
                ->count(3)
                ->sequence(fn() => [
                    'teacher_id' => $user,
                    'student_id' => rand(1, $this->limit),
                    'tip_id'     => $user->tips->random(),
                ])
                ->create();
        });
    }

    /**
     * Populate the database with fake tips.
     *
     */
    protected function seedTips() : void
    {
        User::with('banners')->lazy()->each(function($user) {
            Tip::factory()
                ->count(10)
                ->sequence(fn() => [
                    'user_id'   => $user,
                    'banner_id' => $user->banners->random(),
                ])
                ->create();
        });
    }

    /**
     * Populate the database with fake users.
     *
     */
    protected function seedUsers() : void
    {
        User::factory()
            ->count($this->limit)
            ->create();

        User::first()->update([
            'type'   => UserType::EMPLOYEE,
            'name'   => env('DEVELOPMENT_USER_NAME', 'John Doe'),
            'handle' => env('DEVELOPMENT_USER_HANDLE', 'john'),
            'email'  => env('DEVELOPMENT_USER_EMAIL', 'john@acme.com'),
            'avatar' => null,
        ]);
    }
}
