<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}

    <div class="row">
        <div class="col-md-12">
            <form wire:submit.prevent="updateCategory">

                <div class="form-group mb-4">
                    <label for="category" class="mb-2">Select Category</label>
                    <select wire:model="category" class="form-control" id="category">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
    </div>

</div>
