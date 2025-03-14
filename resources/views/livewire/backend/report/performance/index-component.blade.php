<div>
    {{-- The whole world belongs to you. --}}

    <div class="row">
        <div class="col-md-4">
            {{-- Show Form select month year --}}
            <form wire:submit='submitFilter'>
                <div class="row">
                    <div class="col-md-6">
                        {{-- Select Month --}}
                        <label for="month" class="mb-2 fw-bolder">@lang('Select Month')</label>
                        <input type="month" class="form-control form-control-sm" id="month" wire:model.live="month" min="{{$startMonth}}" max="{{$endMonth}}">
                        @error('month')
                            <small class="text-danger d-inline-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="store" class="mb-2 fw-bolder">@lang('Select Store')</label>
                        <select name="store" id="store" class="form-control form-control-sm" wire:model="store">
                            <option value="">@lang('Select Store')</option>
                            @foreach ($stores as $store2)
                                <option value="{{$store2->id}}">{{$store2->name}}</option>
                            @endforeach
                        </select>
                        @error('store')
                            <small class="text-danger d-inline-block">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="row mt-2">
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-sm btn-primary">@lang('Apply')</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="row">

        <div class="col-md-4">

        </div>

        <div class="col-md-6">
            <h6 class="card-subtitle mb-2">Target Setting</h6>
            <livewire:backend.report.performance.target-setting-component
                :targetCrews="$crewGroups"
                :$month
                :$store/>
        </div>
    </div>

</div>
