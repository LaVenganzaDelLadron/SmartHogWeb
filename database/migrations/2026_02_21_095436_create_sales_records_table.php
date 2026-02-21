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
        Schema::create('sales_records', function (Blueprint $table) {
            $table->string('sales_id')->primary();
            $table->string('batch_id');
            $table->float('avg_weight_kg');
            $table->date('sale_date');
            $table->integer('total_pigs_sold');
            $table->timestamp('record_date')->useCurrent();

            $table->foreign('batch_id')->references('batch_id')->on('pig_batches')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_records');
    }
};
