<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('videos')) {
            Schema::create('videos', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->string('video_path');
                $table->string('script_path');
                $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
                $table->date('upload_date');
                $table->string('voiceover_path')->nullable();
                $table->string('preview_thumbnail')->nullable();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
                $table->enum('status', ['Published', 'Archived', 'Blocked'])->default('Published');
                $table->boolean('comments_enabled')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
