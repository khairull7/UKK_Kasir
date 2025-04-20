<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasFactory;
    
    protected $table = 'pembelian_details'; 
    
    protected $fillable = [
        'pembelian_id',
        'id_produk',      
        'quantity',
        'total_price'     
    ];
    
    public function pembelian()
    {
        return $this->belongsTo(Pembelians::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk'); 
    }
}