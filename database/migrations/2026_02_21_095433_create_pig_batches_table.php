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
        Schema::create('pig_batches', function (Blueprint $table) {
            $table->string('batch_id')->primary();
            $table->integer('no_of_pigs');
            $table->float('avg_weight_kg');
            $table->text('notes')->nullable();
            $table->unsignedInteger('pen_id');
            $table->timestamp('record_date')->useCurrent();

            $table->foreign('pen_id')->references('pen_id')->on('pens')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pig_batches');
    }
};
