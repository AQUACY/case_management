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
        Schema::table('messages', function (Blueprint $table) {
            // Add case_id column to the messages table
            $table->foreignId('case_id')->nullable()->constrained('cases')->onDelete('cascade'); // Ensure it refers to the 'cases' table and delete messages if the case is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Drop the case_id column if migrating down
            $table->dropForeign(['case_id']);
            $table->dropColumn('case_id');
        });
    }
};
