@if ($category->id != 1)
    <x-utils.edit-button :href="route('admin.product-category.edit', ['category' => $category])" />
    <x-utils.delete-button :href="route('admin.product-category.destroy', ['category' => $category])" />
@endif

