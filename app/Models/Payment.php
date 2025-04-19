<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sale_id',
        'payment_method',
        'total_pay',
        'total_return',
        'payment_date'
    ];
    
    protected $dates = [
        'payment_date'
    ];
    
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    
}