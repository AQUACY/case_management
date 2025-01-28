<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('background_review_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('background_information_id');
            $table->text('comment');
            $table->string('status'); // 'pending' or 'approved'
            $table->unsignedBigInteger('commented_by'); // user_id of the case manager
            $table->timestamps();

            $table->foreign('background_information_id')
                  ->references('id')
                  ->on('background_information')
                  ->onDelete('cascade');

            $table->foreign('commented_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('background_review_comments');
    }
};
