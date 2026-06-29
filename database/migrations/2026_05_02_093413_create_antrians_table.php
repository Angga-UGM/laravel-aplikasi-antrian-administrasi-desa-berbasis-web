<?php
/*
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrian')->unique();
            $table->string('nama');
            $table->string('nik', 16);
            $table->string('jenis_kelamin');
            $table->text('alamat');
            $table->string('keperluan');
            $table->enum('status', ['menunggu', 'dipanggil', 'selesai', 'batal'])->default('menunggu');
            $table->date('tanggal_antrian');
            $table->time('waktu_daftar');
            $table->time('waktu_panggil')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};*/
