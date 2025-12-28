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
        Schema::create('slides', function (Blueprint $table) {
              $table->id();

            $table->string('title_fr')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();

            $table->integer('order')->default(0);

            $table->text('content_fr')->nullable();
            $table->text('content_ar')->nullable();
            $table->text('content_en')->nullable();

            $table->foreignId('slider_id')->constrained()->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
