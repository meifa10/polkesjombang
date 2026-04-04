<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan pendaftaran kolom baru.
     */
    public function up(): void
    {
        Schema::table('pendaftaran_poli', function (Blueprint $table) {
            /**
             * Menambahkan kolom user_id setelah kolom id.
             * nullable() : Agar data lama yang sudah ada tidak error saat migrasi.
             * constrained('users') : Menghubungkan kolom ini ke ID di tabel users.
             * onDelete('cascade') : Jika user dihapus, data antriannya juga terhapus.
             */
            $table->foreignId('user_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan perubahan (Rollback).
     */
    public function down(): void
    {
        Schema::table('pendaftaran_poli', function (Blueprint $table) {
            // Menghapus foreign key terlebih dahulu baru menghapus kolomnya
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};