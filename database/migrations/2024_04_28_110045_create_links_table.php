<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id');
            $table->string('url');
            $table->string('topic_title');
            $table->string('page_title');
            $table->string('section_title');
            $table->string('link_title')->nullable();
            $table->timestamps();
        });

        Schema::table('links', function (Blueprint $table) {
            $table->foreign('framework_id')->references('id')->on('frameworks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
