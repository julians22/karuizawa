<x-utils.view-button :href="route('admin.product.show', ['product' => $product])" />
<x-utils.edit-button :href="route('admin.product.edit', ['product' => $product])" />
<x-utils.edit-button
    text="Update Stock"
    :href="route('admin.product.edit-stock', ['product' => $product])" />
{{--  --}}
    {{-- @dump($product) --}}
