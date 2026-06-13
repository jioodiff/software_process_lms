<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('tgl_pengajuan');
            $table->date('tgl_rencana_pinjam');
            $table->date('tgl_rencana_kembali');
            $table->text('keperluan');
            $table->enum('status', [
                'Menunggu Persetujuan',
                'Disetujui',
                'Ditolak',
                'Dipinjam',
                'Dikembalikan'
            ])->default('Menunggu Persetujuan');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('tgl_pengajuan');
            $table->index(['mahasiswa_id', 'status']);
        });

        Schema::create('borrowing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained('borrowings')->cascadeOnDelete();
            $table->foreignId('tool_id')->constrained('tools')->restrictOnDelete();
            $table->unsignedInteger('jumlah_unit')->default(1);
            $table->enum('kondisi_saat_kembali', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->text('catatan_pengembalian')->nullable();

            $table->index('borrowing_id');
            $table->index('tool_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_items');
        Schema::dropIfExists('borrowings');
    }
};
