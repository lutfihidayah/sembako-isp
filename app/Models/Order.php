<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'drop_point_id',
        'status',
        'total_price',
        'payment_method',
        'payment_proof',
        'expired_at',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'expired_at'  => 'datetime',
    ];

    // Status order berurutan
    const STATUS_FLOW = [
        'menunggu_pembayaran',
        'dibayar',
        'sedang_dibelanjakan',
        'dikirim',
        'siap_diambil',
        'selesai',
    ];

    const STATUS_LABELS = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'dibayar'             => 'Dibayar / Diproses',
        'sedang_dibelanjakan' => 'Sedang Dibelanjakan',
        'dikirim'             => 'Dikirim ke Drop Point',
        'siap_diambil'        => 'Siap Diambil',
        'selesai'             => 'Selesai',
        'dibatalkan'          => 'Dibatalkan',
    ];

    const PAYMENT_METHOD_LABELS = [
        'transfer_bank' => 'Transfer Bank',
        'qris'          => 'QRIS',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dropPoint()
    {
        return $this->belongsTo(DropPoint::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('created_at', 'desc');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHOD_LABELS[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Cek apakah status bisa maju ke status berikutnya
     */
    public function canAdvanceTo(string $newStatus): bool
    {
        if ($newStatus === 'dibatalkan') return true;

        $currentIndex = array_search($this->status, self::STATUS_FLOW);
        $newIndex = array_search($newStatus, self::STATUS_FLOW);

        if ($currentIndex === false || $newIndex === false) return false;

        return $newIndex === $currentIndex + 1;
    }

    /**
     * Ambil status berikutnya yang valid
     */
    public function nextStatus(): ?string
    {
        $currentIndex = array_search($this->status, self::STATUS_FLOW);
        if ($currentIndex === false || $currentIndex >= count(self::STATUS_FLOW) - 1) {
            return null;
        }
        return self::STATUS_FLOW[$currentIndex + 1];
    }

    public function getStatusIndexAttribute(): int
    {
        return array_search($this->status, self::STATUS_FLOW) ?: 0;
    }

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $last = self::where('order_number', 'like', "INV-{$date}-%")
            ->orderBy('order_number', 'desc')
            ->value('order_number');

        $seq = $last ? (int) substr($last, -4) + 1 : 1;
        return "INV-{$date}-" . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
