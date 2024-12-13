@extends('backend.layouts.app')

@section('title', __('Create New Product'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-forms.post :action="route('admin.product.store')">

    <x-backend.card>
        <x-slot name="header">
            @lang('Create New Product')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">

            {{-- Category --}}

            <div class="row mb-3">
                <label for="category" class="col-md-2 form-label">@lang('Category')</label>

                <div class="col-md-10">
                    <select name="category_id" id="category" class="form-control" required>
                        <option value="">@lang('Please select category')</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="row mb-3">
                <label for="product_name" class="col-md-2 form-label">@lang('Product Name')</label>

                <div class="col-md-10">
                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="{{ __('Product Name') }}" value="{{ old('product_name') }}" maxlength="100" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="sku" class="col-md-2 form-label">@lang('SKU')</label>

                <div class="col-md-10">
                    <input type="text" name="sku" id="sku" class="form-control" placeholder="{{ __('SKU') }}" value="{{ old('sku') }}" maxlength="100" required />
                </div>
            </div>

            {{-- Product Description --}}
            <div class="row mb-3">
                <label for="description" class="col-md-2 form-label">@lang('Description')</label>

                <div class="col-md-10">
                    <textarea name="description" id="description" class="form-control" placeholder="{{ __('Description') }}" required>{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Price --}}
            <div class="row mb-3">

                <label for="price" class="col-md-2 form-label">@lang('Price')</label>

                <div class="col-md-10">
                    <input type="text" name="price" id="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') }}" maxlength="100" required />
                </div>

            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
        </x-slot>

    </x-backend.card>
</x-forms.post>

@endsection
