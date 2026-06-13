<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tool_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->restrictOnDelete();
            $table->enum('tipe_mutasi', ['Masuk', 'Keluar', 'Penyesuaian']);
            $table->integer('jumlah');
            $table->unsignedInteger('stok_sebelum');
            $table->unsignedInteger('stok_sesudah');
            $table->text('keterangan')->nullable();
            $table->foreignId('dilakukan_oleh')->constrained('users')->restrictOnDelete();
            $table->timestamp('timestamp')->useCurrent();

            $table->index('tool_id');
            $table->index('tipe_mutasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_mutations');
    }
};
