<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

    <form wire:submit.prevent="submit">
        <div class="form-group">
            <label for="target" class="fw-bolder">{{ $this->targetModel->user->name }}</label>
            <div class="mb-2 ">
                <span class="badge bg-success">Current Target {{ price_format($this->targetModel->target) }}</span>
            </div>
            @error('target')
                <small class="text-danger d-inline-block">{{ $message }}</small>
            @enderror
            <div class="input-group">
                <input class="form-control" wire:model="target" x-mask:dynamic="$money($input, '.', ',', 2)">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
        </div>
    </form>

</div>
