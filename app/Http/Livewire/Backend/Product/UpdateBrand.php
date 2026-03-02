<?php

namespace App\Http\Livewire\Backend\Product;

use App\Http\Livewire\Backend\ProductTable;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateBrand extends Component
{
    public $products = [];
    public $brand = 1;

    #[On('bulkActionProduct')]
    public function handleBulkActionProduct($data = [])
    {
        $this->products = $data;
        $this->dispatch('updateProductBrand', data: $this->products);
    }

    public function render()
    {
        $brands = Brand::all();

        return view('livewire.backend.product.update-brand', compact('brands'));
    }

    public function updateBrand(){

        // validate the brand
        $this->validate([
            'brand' => 'required|exists:brands,id',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        DB::beginTransaction();

        // update the brand
        $products = Product::whereIn('id', $this->products)->get();

        foreach ($products as $product) {

            try {
                $product->brand_id = $this->brand;
                $product->save();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispacth('sendNotification', type: 'error', message: 'Failed to update brand');
                return;
            }
        }

        DB::commit();

        $this->dispatch('updatedProductBrand')
            ->to(ProductTable::class);
    }
}
