<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date', 'total_price', 'total_pay', 'total_return',
        'customer_id', 'staff_id', 'poin', 'total_poin'
    ];

    public function customer() { 
        return $this->belongsTo(Customer::class); 
    }
    public function staff() { 
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function details() {
         return $this->hasMany(DetailSale::class); 
    }
}
