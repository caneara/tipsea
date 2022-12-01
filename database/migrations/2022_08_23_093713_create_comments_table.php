<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        Schema::create('comments', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tip_id')->constrained()->cascadeOnDelete();
            $table->string('message', 500);
            $table->timestamps();
        });

        Schema::table('comments', function(Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('tip_id')->constrained('comments')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() : void
    {
        Schema::dropIfExists('comments');
    }
};
