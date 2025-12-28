<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('display_name')->nullable();
            $table->string('real_name');
            $table->string('path'); // Path to the file on the server
            $table->string('url'); // URL to access the file
             $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('size'); // Size of the file in bytes
            $table->string('use_case')->nullable(); // Use case for the file
            $table->morphs('fileable'); // Polymorphic relationship columns (fileable_id and fileable_type)
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
