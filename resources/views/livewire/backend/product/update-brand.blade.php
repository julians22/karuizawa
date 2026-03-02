<div>
    {{-- The whole world belongs to you. --}}

    <div class="row">
        <div class="col-md-12">
            <form wire:submit.prevent="updateBrand">

                <div class="form-group mb-4">
                    <label for="brand" class="mb-2">Select Brand</label>
                    <select wire:model="brand" class="form-control" id="brand">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Update Brand</button>
            </form>
        </div>
    </div>

</div>
