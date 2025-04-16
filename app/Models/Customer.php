<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'no_telp', 'poin'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function usePoin($amount)
{
    $this->poin = max(0, $this->poin - $amount);
    $this->save();
}

public function addPoin($amount)
{
    $this->poin += $amount;
    $this->save();
}

}
