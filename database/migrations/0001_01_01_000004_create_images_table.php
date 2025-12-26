<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('display_name')->nullable(); // Name of the image
            $table->string('real_name'); // Name of the image
            $table->string('path'); // Path of the image in the storage
            $table->string('url'); // URL to access the image
            $table->integer('size'); // Size of the image in bytes
            $table->integer('width'); // Width of the image
            $table->integer('height'); // Height of the image
            $table->boolean('is_active')->default(true);
            $table->string('use_case')->nullable(); // Use case for the image (e.g., profile, logo, etc.)
            $table->morphs('imageable'); // Polymorphic relation fields (imageable_id and imageable_type)
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
        Schema::dropIfExists('images');
    }
};
