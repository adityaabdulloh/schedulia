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
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['jam_id']);
            $table->dropColumn('jam_id');

            // Add new time columns
            $table->time('jam_mulai')->after('hari_id');
            $table->time('jam_selesai')->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_kuliah', function (Blueprint $table) {
            // Drop new time columns
            $table->dropColumn('jam_mulai');
            $table->dropColumn('jam_selesai');

            // Add back the old column and foreign key
            $table->unsignedBigInteger('jam_id')->after('hari_id');
            $table->foreign('jam_id')->references('id')->on('jam')->onDelete('cascade');
        });
    }
};