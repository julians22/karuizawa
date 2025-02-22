@extends('backend.layouts.app')

@section('title', __('Category Management'))

@section('content')

<x-forms.patch :action="route('admin.product-category.update', ['category' => $category])">

<x-backend.card>

    <x-slot name="header">
        @lang('Category Management')
    </x-slot>

    <x-slot name="headerActions">
        <x-utils.link class="card-header-action" :href="route('admin.product-category.index')" :text="__('Cancel')" />
    </x-slot>

    <x-slot name="body">

        <div class="row mb-3">
            <label for="category_name" class="col-md-2 form-label">@lang('Category Name')</label>

            <div class="col-md-10">
                <input type="text" name="category_name" id="category_name" class="form-control" placeholder="{{ __('Category Name') }}" value="{{ old('category_name') ?? $category->name }}" maxlength="100" required />
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
    </x-slot>

</x-backend.card>

</x-forms.patch>

@endsection
