@extends('backend.layouts.app')

@section('title', __('Report'))

@section('content')

@push('after-styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

@endpush


<x-backend.card>
    <x-slot name="header">
        @lang('Welcome :Name', ['name' => $logged_in_user->name])
    </x-slot>

    <x-slot name="body">

        <div class="row">
            <div class="col-12">
                <livewire:backend.report.index-component/>
            </div>
        </div>

    </x-slot>

</x-backend.card>

@endsection

@push('before-scripts')
    <script>
        var chartReport = null;
    </script>
@endpush

@push('after-styles')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush
