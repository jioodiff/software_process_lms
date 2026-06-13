<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat', 20)->unique();
            $table->string('nama_alat', 100);
            $table->string('kategori', 50);
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('stok_total')->default(0);
            $table->unsignedInteger('stok_tersedia')->default(0);
            $table->enum('status_alat', ['Tersedia', 'Tidak Tersedia', 'Rusak', 'Dalam Perbaikan'])->default('Tersedia');
            $table->string('lokasi', 50)->nullable();
            $table->string('foto_alat', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('kategori');
            $table->index('status_alat');
            $table->index('stok_tersedia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
