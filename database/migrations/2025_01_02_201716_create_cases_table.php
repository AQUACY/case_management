<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->decimal('bill', 10, 2); // For monetary amounts
            $table->unsignedBigInteger('case_manager_id')->nullable(); // Case manager's user ID
            $table->foreign('case_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('user_id'); // User ID for the client
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Foreign key to users table
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
}
