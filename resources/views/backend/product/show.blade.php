@extends('backend.layouts.app')

@section('title', __('Show Product'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<div>
    <x-backend.card>
        <x-slot name="header">
            @lang('Show Product') <pre>{{ $product->product_name }}</pre>
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Back')" />
        </x-slot>

        <x-slot name="body">

            {{-- Category --}}

            <div class="row mb-3">
                <label for="category" class="col-md-2 form-label">@lang('Category')</label>

                <div class="col-md-10">
                    <input type="text" disabled value="{{ $product->category->name }}" class="form-control" />
                </div>
            </div>


            <div class="row mb-3">
                <label for="product_name" class="col-md-2 form-label">@lang('Product Name')</label>

                <div class="col-md-10">
                    <input type="text" disabled name="product_name" id="product_name" class="form-control" placeholder="{{ __('Product Name') }}" value="{{ old('product_name') ?? $product->product_name }}" maxlength="100" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="sku" class="col-md-2 form-label">@lang('SKU')</label>

                <div class="col-md-10">
                    <input type="text" disabled name="sku" id="sku" class="form-control" placeholder="{{ __('SKU') }}" value="{{ old('sku') ?? $product->sku }}" maxlength="100" required />
                </div>
            </div>

            {{-- Product Description --}}
            <div class="row mb-3">
                <label for="description" class="col-md-2 form-label">@lang('Description')</label>

                <div class="col-md-10">
                    <textarea name="description" disabled id="description" class="form-control" placeholder="{{ __('Description') }}" required>{{ old('description') ?? $product->description }}</textarea>
                </div>
            </div>

            {{-- Price --}}
            <div class="row mb-3">

                <label for="price" class="col-md-2 form-label">@lang('Price')</label>

                <div class="col-md-10">
                    <input type="text" disabled name="price" id="price" class="form-control" placeholder="{{ __('Price') }}" value="{{ old('price') ?? $product->price }}" maxlength="100" required />
                </div>

            </div>

        </x-slot>

    </x-backend.card>
</div>

@endsection
