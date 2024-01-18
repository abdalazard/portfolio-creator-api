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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->boolean('profile')->default(0);
            $table->boolean('skills')->default(0);
            $table->boolean('projects')->default(0);
            $table->boolean('others')->default(0);
            $table->boolean('contacts')->default(0);
            $table->boolean('is_published')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};
