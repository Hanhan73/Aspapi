<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel terpisah supaya counter per-tahun gampang di-lock & di-query,
        // dan satu kwitansi bisa nyangkut ke banyak Payment (kasus gabungan).
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique(); // contoh: 001/ASPAPI/BD/06/2026
            $table->unsignedInteger('sequence');         // urutan dalam tahun itu
            $table->unsignedSmallInteger('year');         // tahun kwitansi (buat reset counter)
            $table->enum('source_type', ['payment', 'payment_batch']);
            $table->unsignedBigInteger('source_id');     // id Payment (salah satu, kalau gabungan) atau PaymentBatch
            $table->unsignedBigInteger('member_id')->nullable(); // null kalau batch/kolektif
            $table->unsignedBigInteger('region_id')->nullable(); // diisi kalau batch/kolektif
            $table->json('payment_id_list');              // semua Payment id yang tercover kwitansi ini
            $table->decimal('amount', 12, 2);
            $table->string('payer_name');                 // "Telah terima dari"
            $table->string('purpose');                     // "Untuk pembayaran"
            $table->unsignedBigInteger('issued_by')->nullable(); // user bendahara
            $table->timestamps();

            $table->index(['year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
