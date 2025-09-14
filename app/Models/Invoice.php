<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id','invoice_number','invoice_date','due_date',
        'subtotal','discount','tax','total','status'
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function items()    { return $this->hasMany(InvoiceItem::class); }
}

