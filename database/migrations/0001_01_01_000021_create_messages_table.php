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
        Schema::create('messages', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the person sending the message
            $table->string('email'); // Email of the sender
            $table->text('message'); // Content of the message
            $table->timestamps(); // Created at and Updated at
            $table->softDeletes(); // Soft delete (deleted_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
