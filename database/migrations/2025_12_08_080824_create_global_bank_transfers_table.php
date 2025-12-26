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
        Schema::create('global_bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedSmallInteger('number'); // number between 1 and 999
            $table->string('reference')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->text('motive_fr')->nullable();
            $table->text('motive_ar')->nullable();
            $table->text('motive_en')->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_bank_transfers');
    }
};
