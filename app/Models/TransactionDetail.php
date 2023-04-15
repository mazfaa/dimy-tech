<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'payment_id', 'qty'];

    protected $appends = ['price_total'];

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

    public function getPriceTotalAttribute() {
        return $this->product_price * $this->quantity;
    }
}
