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
        Schema::create('filter_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('filter_groups', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });


        Schema::create('filter_group_framework', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_group_id');
            $table->foreignId('framework_id');
        });

        Schema::table('filter_group_framework', function (Blueprint $table) {
            $table->foreign('filter_group_id')->references('id')->on('filter_groups');
            $table->foreign('framework_id')->references('id')->on('frameworks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_groups');
        Schema::dropIfExists('filter_group_framework');
    }
};
