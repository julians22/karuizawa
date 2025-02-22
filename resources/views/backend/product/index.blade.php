@extends('backend.layouts.app')

@section('title', __('Product Management'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Product Management')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.product.create')"
                :text="__('Create Product')"
            />

            {{-- Fetch Stock Product --}}
            <x-utils.link
                icon="c-icon cil-sync"
                class="card-header-action"
                :href="route('admin.product.fetch-stock')"
                :text="__('Fetch Stock')"
            />

            {{-- Fetch Price Product --}}
            <x-utils.link
                icon="c-icon cil-sync"
                class="card-header-action"
                :href="route('admin.product.fetch-price')"
                :text="__('Fetch Product')"
            />

        </x-slot>
    @endif

    <x-slot name="body">
        @livewire('backend.product-table')
    </x-slot>

</x-backend.card>

@push('before-scripts')

{{-- Modal for update category product --}}
<div class="modal fade" id="updateProductCategory" tabindex="-1" aria-labelledby="updateProductCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProductCategoryLabel">Update Product Category</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @livewire('backend.product.update-category')
            </div>
        </div>
    </div>
</div>

@endpush

@push('after-scripts')


<script>

    document.addEventListener('livewire:init', () => {
        let modalElement = document.getElementById('updateProductCategory')
        const updateProductCategory = new coreui.Modal(modalElement);

        Livewire.on('updateProductCategory', ({data}) => {
            updateProductCategory.show();
        });

        Livewire.on('sendNotification', ({type = 'success', message}) => {

            updateProductCategory.hide();
            if (type === 'success') {

                Swal.fire({
                    title: 'Success!',
                    text: message,
                    icon: 'success',
                    confirmButtonText: 'Close'
                });

            } else {


                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });

            }

        });
    });

</script>

@endpush

@endsection
