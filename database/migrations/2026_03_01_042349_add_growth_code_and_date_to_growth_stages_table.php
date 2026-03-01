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
        Schema::table('growth_stages', function (Blueprint $table) {
            $table->string('growth_code')->nullable()->unique()->after('growth_id');
            $table->dateTime('date')->nullable()->after('growth_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('growth_stages', function (Blueprint $table) {
            $table->dropUnique(['growth_code']);
            $table->dropColumn(['growth_code', 'date']);
        });
    }
};
