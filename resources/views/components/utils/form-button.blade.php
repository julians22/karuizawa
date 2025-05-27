@props([
    'action' => '#',
    'method' => 'POST',
    'name' => '',
    'formClass' => 'd-inline',
    'buttonClass' => '',
    'icon' => false,
    'permission' => false,
    'dataTitle' => __('Are you sure you want to do this?')
])

@if ($permission)
    @if ($logged_in_user->can($permission))
        <form method="POST" data-title="{{ $dataTitle }}" action="{{ $action }}" name="{{ $name }}" class="{{ $formClass }}">
            @csrf
            @method($method)

            <button type="submit" class="{{ $buttonClass }}">
                @if ($icon)<i class="{{ $icon }}"></i> @endif{{ $slot }}
            </button>
        </form>
    @endif
@else
    <form method="POST" data-title="{{ $dataTitle }}" action="{{ $action }}" name="{{ $name }}" class="{{ $formClass }}">
        @csrf
        @method($method)

        <button type="submit" class="{{ $buttonClass }}">
            @if ($icon)<i class="{{ $icon }}"></i> @endif{{ $slot }}
        </button>
    </form>
@endif
