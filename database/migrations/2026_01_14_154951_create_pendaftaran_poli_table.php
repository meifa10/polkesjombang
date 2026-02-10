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
        Schema::create('pendaftaran_poli', function (Blueprint $table) {
            $table->id();

            // jenis pasien
            $table->enum('jenis_pasien', ['JKN', 'UMUM']);

            // JKN
            $table->string('nomor_bpjs')->nullable();
            $table->string('asal_rujukan')->nullable();

            // UMUM
            $table->enum('identitas', ['KTP', 'RM'])->nullable();
            $table->string('nomor_identitas')->nullable();

            // umum
            $table->date('tanggal_lahir');
            $table->date('tanggal_daftar')->default(now());

            // status untuk admin/dokter
            $table->enum('status', ['MENUNGGU', 'DITERIMA', 'DITOLAK'])
                ->default('MENUNGGU');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_poli');
    }
};
