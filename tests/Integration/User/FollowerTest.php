<?php

namespace Tests\Integration\User;

use App\Models\User;
use App\Models\Follower;
use App\Types\ServerTest;

class FollowerTest extends ServerTest
{
    /** @test */
    public function a_user_can_start_following_a_teacher() : void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $this->actingAs($student)
            ->postJson(route('follower.store', ['teacher' => $teacher]))
            ->assertExactJson(['done']);

        $this->assertCount(1, Follower::get());

        $this->assertTrue(Follower::first()->student->is($student));
        $this->assertTrue(Follower::first()->teacher->is($teacher));
    }

    /** @test */
    public function a_user_cannot_start_following_a_teacher_if_the_teacher_and_the_user_are_the_same_person() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('follower.store', ['teacher' => $user]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_stop_following_a_teacher() : void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();

        Follower::factory()->create([
            'teacher_id' => $teacher,
            'student_id' => $student,
        ]);

        $this->actingAs($student)
            ->deleteJson(route('follower.delete', ['teacher' => $teacher]))
            ->assertExactJson(['done']);

        $this->assertCount(0, Follower::get());
    }

    /** @test */
    public function a_user_cannot_stop_following_a_teacher_if_they_are_not_following_them() : void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $this->actingAs($student)
            ->deleteJson(route('follower.delete', ['teacher' => $teacher]))
            ->assertForbidden();
    }
}
