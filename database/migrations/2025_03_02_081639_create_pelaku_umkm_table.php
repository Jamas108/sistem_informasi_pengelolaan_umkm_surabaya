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
        Schema::create('pelaku_umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users');
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->string('no_kk');
            $table->string('tempat_lahir');
            $table->string('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('status_hubungan_keluarga');
            $table->string('status_perkawinan');
            $table->string('alamat');
            $table->string('kelurahan');
            $table->string('rw');
            $table->string('rt');
            $table->string('alamat_sesuai_ktp');
            $table->string('no_telp');
            $table->string('pendidikan_terakhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaku_umkm');
    }
};
