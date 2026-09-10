<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Product Model
 *
 * @description Model to represent products (Ready To Wear)
 */
class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'sku',
        'product_name',
        'description',
        'price',
        'category_id',
        'daily_stock',
        'commerce_title',
        'commerce_description',
        'commerce_price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'commerce_description' => 'array',
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

    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

    /**
     * Scope products that are currently allowed to be sold online (config-driven
     * category whitelist) and have live stock in product_actual_stocks.
     */
    public function scopeOnlineSellable(Builder $query, ?int $store_id = null): void
    {
        $allowedCategories = config('catalog.online_categories', ['perfume']);

        $query->whereHas('category', function ($query) use ($allowedCategories) {
            $query->whereIn('slug', $allowedCategories);
        })
        ->where('price', '>', 0)
        ->whereHas('productActualStocks', function ($query) use ($store_id) {
            $query->where('stock_quantity', '>', 0);
            if ($store_id) {
                $query->where('store_id', $store_id);
            }
        });
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_image')
            ->withResponsiveImages();
            // ->useFallbackUrl('/images/anonymous-user.jpg')
            // ->useFallbackPath(public_path('/images/anonymous-user.jpg'));
    }
}
