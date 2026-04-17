@extends('backend.layouts.app')

@section('title', __('Update Product Stock'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-forms.patch :action="route('admin.product.update-stock', ['product' => $product])">


    <div class="row">
        <div class="col-md-12">
             <x-backend.card>
                <x-slot name="header">
                    @lang('Updated Product Stock')
                </x-slot>

                <x-slot name="headerActions">
                    <x-utils.link class="card-header-action" :href="route('admin.product.index')" :text="__('Cancel')" />
                </x-slot>

                <x-slot name="body">

                    {{-- Product Name --}}
                    <div class="mb-3 row">
                        <h5 class="col-md-12">@lang('Product'): {{ $product->product_name }}</h5>
                        <h5 class="col-md-12">@lang('SKU'): {{ $product->sku }}</h5>
                    </div>

                    @foreach ($stores as $store)
                        <div class="mb-3 row">
                            <label for="count_{{ $store->id }}" class="col-md-2 form-label">{{$store->name}}</label>

                            <div class="col-md-10">
                                <input type="number"
                                    name="stocks[{{ $store->id }}]"
                                    id="count_{{ $store->id }}"
                                    class="form-control"
                                    placeholder="{{ __('Stock Unit') }}"
                                    value="{{ old('stocks.'.$store->id) ?? $actualStocks[$store->id] }}"
                                    maxlength="100" required />
                            </div>
                        </div>
                    @endforeach

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
