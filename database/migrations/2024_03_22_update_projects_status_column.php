<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // First drop the existing status column if it has an enum constraint
            $table->dropColumn('status');

            // Then add it back with the new allowed values
            $table->enum('status', ['pending', 'review', 'approved'])->default('pending');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('status');
            // Restore the original status column
            $table->enum('status', ['pending', 'approved', 'review'])->default('pending');
        });
    }
};
