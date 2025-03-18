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
            <label for="category" class="fw-bolder">Category</label>
            <select name="category" id="category" class="form-control" wire:model="category">
                <option value="">Select Category</option>
                @foreach ($this->categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                <option value="semicustom">Semi Custom</option>
            </select>
            @error('category')
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
            <button type="submit" class="btn btn-sm btn-ghost-primary">Save</button>
            <button type="button" class="btn btn-sm btn-ghost-danger" wire:click="cancelCreate">Cancel</button>
        </div>

    </form>
</div>
