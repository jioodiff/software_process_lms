<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 100);
            $table->string('kategori', 50)->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->string('kondisi', 50)->nullable();
            $table->string('lokasi', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('item_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();
            $table->enum('tipe_mutasi', ['Masuk', 'Keluar', 'Penyesuaian']);
            $table->integer('jumlah');
            $table->unsignedInteger('stok_sebelum');
            $table->unsignedInteger('stok_sesudah');
            $table->text('keterangan')->nullable();
            $table->foreignId('dilakukan_oleh')->constrained('users')->restrictOnDelete();
            $table->timestamp('timestamp')->useCurrent();

            $table->index('item_id');
            $table->index('tipe_mutasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_mutations');
        Schema::dropIfExists('items');
    }
};
