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
        // Jika tabel sudah ada, lewati pembuatan (berguna saat DB sudah ada tabel)
        if (!Schema::hasTable('divisions')) {
            Schema::create('divisions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // Nama Divisi wajib diisi dan unik
                $table->text('description')->nullable(); // Deskripsi opsional
                $table->unsignedBigInteger('leader_id')->unique(); // Ketua Divisi (relasi ke User)
                $table->date('formed_date')->useCurrent(); // Tanggal Dibentuk otomatis
                $table->timestamps();

                // Relasi foreign key untuk Ketua Divisi
                $table->foreign('leader_id')->references('id')->on('users')->onDelete('restrict');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
