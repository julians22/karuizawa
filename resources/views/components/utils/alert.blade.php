@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close')])

@php
    $dismissableClass = $dismissable ? 'alert-dismissible' : '';
@endphp

<div {{ $attributes->merge(['class' => 'fade show alert alert-'.$type. ' ' . $dismissableClass]) }} role="alert">
    @if ($dismissable)
        <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="{{ $ariaLabel }}">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif

    {{ $slot }}
</div>
