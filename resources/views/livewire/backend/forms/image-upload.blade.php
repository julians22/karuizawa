<div>
    {{-- Stop trying to control. --}}
    <x-filepond::upload wire:model="file" />

    <div>
        @if($featured_image)
            <img src="{{ $featured_image }}" alt="Preview" class="img-fluid" />
        @endif
    </div>

</div>
