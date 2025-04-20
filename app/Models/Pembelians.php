<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelians extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_name',
        'invoice_number',
        'grand_total',
        'tanggal',
        'dibuat_oleh'
    ];

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'pembelian_details')
                    ->withPivot('quantity', 'subtotal');
    }    

    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelians::class);
    }
}
