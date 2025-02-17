@extends('backend.layouts.app')

@section('title', __('Edit Store'))

@section('content')

<x-forms.patch :action="route('admin.store.update', ['store' => $store])">

    <x-backend.card>
        <x-slot name="header">
            @lang('Edit Store')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.store.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">
            <div class="row mb-3">
                <label for="name" class="col-md-2 form-label">@lang('Name')</label>

                <div class="col-md-10">
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name') ?? $store->name }}" maxlength="100" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-md-2 form-label">@lang('Store Code')</label>

                <div class="col-md-10">
                    <input type="text" name="code" id="code" class="form-control" placeholder="{{ __('Store Code') }}" value="{{ old('code') ?? $store->code }}" maxlength="100" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="phone" class="col-md-2 form-label">@lang('Accurate Alias')</label>

                <div class="col-md-10">
                    <input type="text" name="accurate_alias" id="accurate_alias" class="form-control" placeholder="{{ __('Accurate Alias') }}" value="{{ old('accurate_alias') ?? $store->accurate_alias }}" maxlength="100" />
                </div>
            </div>

            <div class="row mb-3">
                <label for="address" class="col-md-2 form-label">@lang('Address')</label>

                <div class="col-md-10">
                    <textarea name="address" id="address" class="form-control" placeholder="{{ __('Address') }}" required>{{ old('address') ?? $store->address }}</textarea>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
        </x-slot>

    </x-backend.card>
</x-forms.patch>

@endsection
