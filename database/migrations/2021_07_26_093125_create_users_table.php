<?php

use App\Enums\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        Schema::create('users', function(Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->default(UserType::CUSTOMER->value);
            $table->string('name', 50);
            $table->string('handle', 30)->unique();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('biography', 500)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('donate', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('github', 100)->nullable();
            $table->string('linkedin', 100)->nullable();
            $table->string('youtube', 100)->nullable();
            $table->string('facebook', 100)->nullable();
            $table->uuid('avatar')->nullable();
            $table->json('metrics')->default(DB::raw('(JSON_OBJECT())'));
            $table->json('settings')->default(DB::raw('(JSON_OBJECT())'));
            $table->mediumText('integration')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() : void
    {
        Schema::dropIfExists('users');
    }
}
