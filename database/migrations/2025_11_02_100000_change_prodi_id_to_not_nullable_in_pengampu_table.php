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
        Schema::table('pengampu', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengampu', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable()->change();
        });
    }
};
