<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        Schema::create('tips', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('banner_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title', 100)->fullText();
            $table->string('slug', 125)->nullable()->index();
            $table->string('summary', 200);
            $table->string('teaser', 1000);
            $table->string('theme', 5);
            $table->unsignedTinyInteger('gradient');
            $table->string('first_tag', 20);
            $table->string('second_tag', 20)->nullable();
            $table->string('third_tag', 20)->nullable();
            $table->string('fourth_tag', 20)->nullable();
            $table->uuid('card')->nullable();
            $table->text('content');
            $table->string('attribution', 100)->nullable();
            $table->json('metrics')->default(DB::raw('(JSON_OBJECT())'));
            $table->boolean('shared')->default(false)->index();
            $table->dateTime('published_at')->nullable()->index();
            $table->timestamps();

            $table->index(['first_tag', 'second_tag', 'third_tag', 'fourth_tag']);
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() : void
    {
        Schema::dropIfExists('tips');
    }
}
