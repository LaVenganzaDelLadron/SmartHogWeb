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
        Schema::create('pig_growth_records', function (Blueprint $table) {
            $table->string('record_id')->primary();
            $table->string('batch_id');
            $table->integer('pig_age_days');
            $table->float('avg_weight_kg');
            $table->string('growth_stage');
            $table->timestamp('record_date')->useCurrent();

            $table->foreign('batch_id')->references('batch_id')->on('pig_batches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pig_growth_records');
    }
};
