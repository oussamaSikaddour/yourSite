<?php

use App\Enum\Core\NotificationFor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->boolean('active')->default(true);
            /* -------------------------------------------------
             * Native ENUM column (MySQL 8.0.17+ / PostgreSQL)
             * ------------------------------------------------- */
            $table->enum('for_type', array_column(NotificationFor::cases(), 'value'))
                  ->default(NotificationFor::USER->value);
            $table->unsignedBigInteger('targetable_id')->nullable();
            $table->string('targetable_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Indexes
            $table->index('active');
            $table->index('for_type');
            $table->index(['targetable_type', 'targetable_id']);
            // Full-text search (MySQL / PostgreSQL)
            $table->fullText('message');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
