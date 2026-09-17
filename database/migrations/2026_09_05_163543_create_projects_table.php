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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->string('client')->nullable();
            $table->string('location')->nullable();
            $table->string('years');
            $table->json('images')->nullable();
            $table->string('description');
            $table->longText('content');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};