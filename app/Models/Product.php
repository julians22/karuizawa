<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Product Model
 *
 * @description Model to represent products (Ready To Wear)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_name',
        'description',
        'price',
        'category_id',
        'daily_stock',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function productActualStocks()
    {
        return $this->hasMany(ProductActualStock::class);
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'product');
    }

    public function scopeSku($query, $sku)
    {
        return $query->where('sku', $sku);
    }

    public function scopeValidProduct(Builder $query, int $store_id = 0): void
    {

        if (!$store_id) {
            $query->whereHas('productActualStocks', function ($query) {
                $query->where('stock_quantity', '>', 0);
            });
        }else{
            $query->whereHas('productActualStocks', function ($query) use ($store_id) {
                $query->where('store_id', $store_id)
                    ->where('stock_quantity', '>', 0);
            });
        }

        $query->where('price', '>', 0);
    }
}
