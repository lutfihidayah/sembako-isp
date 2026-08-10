<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('drop_point_id')->constrained('drop_points');
            $table->enum('status', [
                'menunggu_pembayaran',
                'dibayar',
                'sedang_dibelanjakan',
                'dikirim',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_pembayaran');
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['transfer_bank', 'qris'])->default('transfer_bank');
            $table->string('payment_proof')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
