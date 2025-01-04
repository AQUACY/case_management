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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User who created the message
            $table->unsignedBigInteger('case_manager_id')->nullable(); // Case manager responding to the message
            $table->unsignedBigInteger('category_id'); // Message category
            $table->string('subject'); // Subject of the message
            $table->text('message'); // The user's message
            $table->text('response')->nullable(); // Response from the case manager
            $table->enum('status', ['pending', 'answered', 'archived'])->default('pending'); // Message status
            $table->integer('rating')->nullable(); // Rating given by the user for the response
            $table->timestamps();

            // Define foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('case_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('message_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
