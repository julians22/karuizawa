@extends('backend.layouts.app')

@section('title', __('Update Product'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-forms.patch :action="route('admin.product.update', ['product' => $product])">


    <div class="row">
        <div class="col-md-12">
             <x-backend.card>
                <x-slot name="header">
                    @lang('Updated Product')
                </x-slot>

                <x-slot name="headerActions">
                    <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
                </x-slot>

                <x-slot name="body">

                    {{-- Category --}}

                    <div class="mb-3 row">
                        <label for="category" class="col-md-2 form-label">@lang('Category')</label>

                        <div class="col-md-10">
                            <select name="category_id" id="category" class="form-control" required>
                                <option value="">@lang('Please select category')</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category') == $category->id || $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="product_name" class="col-md-2 form-label">@lang('Product Name')</label>

                        <div class="col-md-10">
                            <input type="text" name="product_name" id="product_name" class="form-control" placeholder="{{ __('Product Name') }}" value="{{ old('product_name') ?? $product->product_name }}" maxlength="100" required />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="sku" class="col-md-2 form-label">@lang('SKU')</label>

                        <div class="col-md-10">
                            <input type="text" name="sku" id="sku" class="form-control" placeholder="{{ __('SKU') }}" value="{{ old('sku') ?? $product->sku }}" maxlength="100" required />
                        </div>
                    </div>

                    {{-- Product Description --}}
                    <div class="mb-3 row">
                        <label for="description" class="col-md-2 form-label">@lang('Description')</label>

                        <div class="col-md-10">
                            <textarea name="description" id="description" class="form-control" placeholder="{{ __('Description') }}" required>{{ old('description') ?? $product->description }}</textarea>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-3 row">

                        <label for="price" class="col-md-2 form-label">@lang('Price')</label>

                        <div class="col-md-10">
                            <input type="text" name="price" id="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') ?? $product->price }}" maxlength="100" required />
                        </div>

                    </div>

                    <div>
                        <h4 class="mb-1 row">Commerce Details</h4>

                        <div>
                            <label class="mb-2" for="formGroupExampleInput">@lang('Commerce Title')</label>
                            <input type="text" name="commerce_title" class="form-control" value="{{ old('commerce_title') ?? $product->commerce_title }}" placeholder="{{ __('Commerce Title') }}">
                        </div>

                        <div class="form-group">
                            <label class="mb-2" for="formGroupExampleInput">@lang('Product Image')</label>
                            @livewire('backend.forms.image-upload', ['featured_image' => $product->getFirstMedia('featured_image') ? $product->getFirstMediaUrl('featured_image') : null])
                        </div>

                        <div class="form-group">
                            <label class="mb-2" for="formGroupExampleInput">@lang('Commerce Description')</label>
                            <textarea id="commerce_description" name="commerce_description" hidden>{{ old('commerce_description') ?? $product->commerce_description }}</textarea>
                            <div id="commerce-description-editor"></div>
                        </div>

                        <div class="form-group">
                            <label class="mb-2" for="formGroupExampleInput">@lang('Commerce Price')</label>
                            <input type="text" name="commerce_price" class="form-control" value="{{ old('commerce_price') ?? $product->commerce_price }}" placeholder="{{ __('Commerce Price') }}">
                        </div>

                    </div>

                </x-slot>

                <x-slot name="footer">
                    <button class="float-right btn btn-sm btn-primary" type="submit">@lang('Save')</button>
                </x-slot>

            </x-backend.card>
        </div>
    </div>
</x-forms.patch>

@endsection

@push('after-scripts')
    @filepondScripts

    <script>

        var initialDescription = @json(old('commerce_description') ?? $product->commerce_description);

        const container = document.getElementById('commerce-description-editor');
        const quill = new Quill(container, {
            modules: {
                toolbar: [
                    [{'header': [2, 3, 4, 5, 6, false]}],
                    ['bold', 'italic'],
                    ['link'],
                    [{ list: 'ordered' }, { list: 'bullet'}],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'color': [] }, { 'background': [] }],
                ],
            },
            theme: 'snow',
        });

         // Set initial content
        quill.setContents(JSON.parse(initialDescription));

        // Update hidden textarea on quilleditor change
        quill.on('text-change', function() {
            let content = quill.getContents().ops;
            document.getElementById('commerce_description').value = JSON.stringify(content);
        });

    </script>

@endpush
