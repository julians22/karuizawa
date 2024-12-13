<x-utils.view-button :href="route('admin.product.show', ['product' => $product])" />
<x-utils.edit-button :href="route('admin.product.edit', ['product' => $product])" />
{{--  --}}
    {{-- @dump($product) --}}
