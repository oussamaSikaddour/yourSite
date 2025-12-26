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
        Schema::create('trends', function (Blueprint $table) {
         $table->id();
            // Multilingual title fields
            $table->string('title_ar');
            $table->string('title_fr')->nullable();
            $table->string('title_en');
            // State of the trend (e.g., draft, published, archived)
            $table->enum('state', ['published', 'not_published'])->default('not_published');
            // Multilingual content fields
            $table->text('content_ar');
            $table->text('content_fr')->nullable();
            $table->text('content_en');
            // Reference to user who created the trend
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Active period
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trends');
    }
};
