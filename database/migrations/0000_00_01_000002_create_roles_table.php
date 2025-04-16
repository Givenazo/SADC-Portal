<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });

            // Seed default roles
            DB::table('roles')->insert([
                ['name' => 'Admin'],
                ['name' => 'Broadcaster'],
                ['name' => 'Viewer'],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
