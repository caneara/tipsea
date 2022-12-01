<?php

use Triggers\Trigger;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Migrations\Migration;

class CreateMetricTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        $stub = File::get(database_path('stubs/count.stub'));

        $this->install($stub, 'users', [
            'tips'          => 'user_id',
            'followers'     => 'teacher_id',
            'notifications' => 'teacher_id',
        ]);

        $this->install($stub, 'tips', [
            'likes'    => 'tip_id',
            'comments' => 'tip_id',
        ]);
    }

    /**
     * Assign a trigger to the given table relations.
     *
     */
    protected function install(string $stub, string $table, array $relations) : void
    {
        foreach ($relations as $relation => $key) {
            $sql = str_replace(['{TABLE}', '{ID}', '{NAME}'], [$table, $key, $relation], $stub);

            Trigger::table($relation)->key($table)->afterInsert(function() use ($sql) {
                return str_replace(['{OPERATOR}', '{ROW}'], ['+', 'NEW'], $sql);
            });

            Trigger::table($relation)->key($table)->afterDelete(function() use ($sql) {
                return str_replace(['{OPERATOR}', '{ROW}'], ['-', 'OLD'], $sql);
            });
        }
    }
};
