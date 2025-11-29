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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('leave_type'); // Cuti Tahunan atau Cuti Sakit
            $table->date('start_date'); // Tanggal mulai
            $table->date('end_date'); // Tanggal selesai
            $table->integer('total_days'); // Total hari cuti (hari kerja)
            $table->text('reason'); // Alasan cuti
            $table->string('attachment')->nullable(); // Surat dokter (jika cuti sakit)
            $table->string('address_during_leave')->nullable(); // Alamat selama cuti
            $table->string('emergency_contact')->nullable(); // Nomor darurat 

            // Status Approval 
            $table->string('status')->default('Pending'); // Pending, Approved by Leader, Approved, Rejected, Cancelled 
            $table->timestamp('leader_approved_at')->nullable();
            $table->text('leader_rejection_note')->nullable();
            $table->timestamp('hrd_approved_at')->nullable();
            $table->text('hrd_note')->nullable(); // Catatan HRD/penolakan 

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
