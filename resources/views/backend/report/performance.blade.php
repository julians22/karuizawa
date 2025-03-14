@extends('backend.layouts.app')

@section('title', __('Performance Report'))

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
        @lang('Performance Report')
    </x-slot>

    <x-slot name="body">

        <div class="row">
            <div class="col-12">
                <livewire:backend.report.performance.index-component/>
            </div>
        </div>

    </x-slot>

</x-backend.card>

@endsection
