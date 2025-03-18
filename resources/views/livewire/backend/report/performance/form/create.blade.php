<div>
    {{-- Be like water. --}}

    <form wire:submit.prevent="addTarget">

        <div class="mb-3">
            <label for="crew" class="fw-bolder">Crew</label>
            <select name="crew" id="crew" class="form-control" wire:model="crew">
                <option value="">Select Crew</option>
                @foreach ($this->availableCrews as $crew)
                    <option value="{{ $crew->id }}">{{ $crew->name }}</option>
                @endforeach
            </select>
            @error('crew')
                <small class="text-danger d-inline-block">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="target" class="fw-bolder">Target</label>
            <input class="form-control" wire:model="target" wire:model="target" x-mask:dynamic="$money($input, '.', ',', 2)">
            @error('target')
                <small class="text-danger d-inline-block">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3 flex">
            <button type="submit" class="btn btn-sm btn-primary">Save</button>
            <button type="button" class="btn btn-sm btn-secondary" wire:click="cancelCreate">Cancel</button>
        </div>

    </form>
</div>
