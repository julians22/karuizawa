@extends('backend.layouts.app')

@section('title', __('Promo New Store'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-forms.post :action="route('admin.promo.store')">

    <x-backend.card>
        <x-slot name="header">
            @lang('Promo New Store')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link class="card-header-action" :href="route('admin.promo.index')" :text="__('Cancel')" />
        </x-slot>

        <x-slot name="body">
            <div class="row mb-3">
                <label for="name" class="col-md-2 form-label">@lang('Promo Label')</label>

                <div class="col-md-10">
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Promo Label') }}" value="{{ old('name') }}" maxlength="100" required />
                </div>
            </div>

            <div class="row mb-3">
                <label for="value" class="col-md-2 form-label">@lang('Promo Value in Numeric')</label>

                <div class="col-md-10">
                    <input type="text" name="value" id="value" class="form-control" placeholder="{{ __('Promo Value in Numeric') }}" value="{{ old('value') }}" maxlength="100" required />
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
        </x-slot>

    </x-backend.card>
</x-forms.post>

@endsection
