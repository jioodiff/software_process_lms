<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Audit trail: APPEND-ONLY, no FK constraints to other tables
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('timestamp')->useCurrent();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_user', 100)->nullable();
            $table->enum('role_pelaku', ['Admin', 'Mahasiswa', 'System'])->default('System');
            $table->string('modul', 50);
            $table->string('aksi', 100);
            $table->string('id_record', 50)->nullable();
            $table->json('data_sebelum')->nullable();
            $table->json('data_sesudah')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->index('timestamp');
            $table->index('modul');
            $table->index('aksi');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
