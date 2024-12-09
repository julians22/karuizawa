<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_name',
        'description',
        'price',
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function additions()
    {
        return $this->hasMany(StockMovement::class)->where('type', 'addition');
    }

    public function subtractions()
    {
        return $this->hasMany(StockMovement::class)->where('type', 'subtraction');
    }

    // Accessor to calculate current stock
    public function getCurrentStockAttribute()
    {
        $totalAdded = $this->additions()->sum('quantity');
        $totalSold = $this->subtractions()->sum('quantity');

        return $totalAdded - $totalSold;
    }
}
