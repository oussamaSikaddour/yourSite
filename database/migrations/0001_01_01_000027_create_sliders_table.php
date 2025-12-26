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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();

            // Slider name
            $table->string('name');

            // Publication state
            $table->enum('state', ['published', 'not_published'])
                ->default('not_published')
                ->index();

            // Creator reference
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('sliderable_id')->nullable()->index();
            $table->string('sliderable_type', 150)->nullable()->index();

            // Position for ordering
            $table->unsignedInteger('position')->default(1)->index();

            // Soft delete & timestamps
            $table->softDeletes();
            $table->timestamps();

            // Optional compound index for faster lookups when reordering
            $table->index(['sliderable_id', 'sliderable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
