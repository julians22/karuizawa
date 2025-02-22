<?php

namespace App\Http\Livewire\Backend\Product;

use App\Http\Livewire\Backend\ProductTable;
use App\Models\Category;
use App\Models\Product;
use DB;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPSTORM_META\type;

class UpdateCategory extends Component
{
    public $products = [];
    public $category = 1;

    #[On('bulkActionProduct')]
    public function handleBulkActionProduct($data = [])
    {
        $this->products = $data;
        $this->dispatch('updateProductCategory', data: $this->products);
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.backend.product.update-category', compact('categories'));
    }

    public function updateCategory(){

        // validate the category
        $this->validate([
            'category' => 'required|exists:categories,id',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        DB::beginTransaction();

        // update the category
        $products = Product::whereIn('id', $this->products)->get();

        foreach ($products as $product) {

            try {
                $product->category_id = $this->category;
                $product->save();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispacth('sendNotification', type: 'error', message: 'Failed to update category');
                return;
            }
        }

        DB::commit();

        $this->dispatch('updatedProductCategory')
            ->to(ProductTable::class);
    }
}
