<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE pens SET pen_code = CONCAT('PEN-', LPAD(pen_id, 3, '0')) WHERE pen_code IS NULL OR pen_code = ''");

        Schema::table('pig_batches', function (Blueprint $table): void {
            $table->dropForeign(['pen_id']);
        });

        DB::statement('UPDATE pig_batches pb JOIN pens p ON pb.pen_id = p.pen_id SET pb.pen_id = p.pen_code');
        DB::statement('ALTER TABLE pig_batches MODIFY pen_id VARCHAR(20) NOT NULL');

        Schema::table('pig_batches', function (Blueprint $table): void {
            $table->foreign('pen_id')->references('pen_code')->on('pens')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pig_batches', function (Blueprint $table): void {
            $table->dropForeign(['pen_id']);
        });

        DB::statement('UPDATE pig_batches pb JOIN pens p ON pb.pen_id = p.pen_code SET pb.pen_id = p.pen_id');
        DB::statement('ALTER TABLE pig_batches MODIFY pen_id INT UNSIGNED NOT NULL');

        Schema::table('pig_batches', function (Blueprint $table): void {
            $table->foreign('pen_id')->references('pen_id')->on('pens')->cascadeOnDelete();
        });
    }
};
