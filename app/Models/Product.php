<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Accessor to calculate current stock
    public function getCurrentStockAttribute()
    {
        $totalAdded = $this->stockMovements()->where('type', 'addition')->sum('quantity');
        $totalSold = $this->stockMovements()->where('type', 'subtraction')->sum('quantity');

        return $totalAdded - $totalSold;
    }
}
