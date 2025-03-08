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
        Schema::create('legalitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')->constrained('umkm');
$table->string('no_sk_nib')->nullable();
$table->string('no_sk_siup')->nullable();
            $table->string('no_sk_tdp');
            $table->string('no_sk_pirt');
            $table->string('no_sk_bpom');
            $table->string('no_sk_halal');
            $table->string('no_sk_merek');
            $table->string('no_sk_haki');
            $table->string('no_surat_keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legalitas');
    }
};
