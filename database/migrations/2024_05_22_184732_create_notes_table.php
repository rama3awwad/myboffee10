<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
              $table->id();
            $table->foreignId('user_id')->refernces('id')->on('users')->onDelete('cascade');
            $table->foreignId('book_id') ->references('id')->on('books')->onDelete('cascade');
            $table->integer('page_num');
            $table->string('body');
            $table->integer('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
};
