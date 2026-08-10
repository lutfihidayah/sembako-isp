<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status',
        'changed_by',
        'note',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return Order::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
