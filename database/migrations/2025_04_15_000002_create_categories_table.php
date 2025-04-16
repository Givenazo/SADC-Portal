<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });

            // Seed default categories
            DB::table('categories')->insert([
                ['name' => 'Sport'],
                ['name' => 'Politics'],
                ['name' => 'Economics'],
                ['name' => 'Technology'],
                ['name' => 'Health'],
                ['name' => 'Others'],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
