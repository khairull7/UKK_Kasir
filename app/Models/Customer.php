<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'no_telp', 'poin'];

    /**
     * Add points to the customer
     * 
     * @param int $points
     * @return bool
     */
    public function addPoin($points)
    {
        $this->poin += $points;
        return $this->save();
    }

    // /**
    //  * Use points from customer balance
    //  * 
    //  * @param int $points
    //  * @return bool
    //  */
    public function usePoin($points)
    {
        if ($this->poin < $points) {
            return false;
        }
        
        $this->poin -= $points;
        return $this->save();
    }

    /**
     * Get sales for this customer
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}