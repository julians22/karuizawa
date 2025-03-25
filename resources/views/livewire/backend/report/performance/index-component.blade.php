<div>
    {{-- The whole world belongs to you. --}}

    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    {{-- Show Form select month year --}}
                    <form wire:submit='submitFilter'>
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Select Month --}}
                                <label for="month" class="mb-2 fw-bolder">@lang('Select Month')</label>
                                <input type="month" class="form-control form-control-sm" id="month" wire:model="month" min="{{$startMonth}}" max="{{$endMonth}}">
                                @error('month')
                                    <small class="text-danger d-inline-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
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

                            <div class="col-md-12">
                                <label for="crew" class="mb-2 fw-bolder">@lang('Select Crew')</label>
                                <select name="crew" id="crew" class="form-control form-control-sm" wire:model="selectedCrew">
                                    <option value="">@lang('Select Crew')</option>
                                    @foreach ($crews as $crew)
                                        <option value="{{$crew->id}}">{{$crew->name}}</option>
                                    @endforeach
                                </select>
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

        </div>

        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    @if (!$this->isReady)
                        <div class="alert alert-danger" role="alert">
                            @lang('Please select month and store')
                        </div>

                    @elseif ($this->isReady && !$selectedCrew)
                        <div class="alert alert-danger" role="alert">
                            @lang('Please select crew')
                        </div>
                    @else
                        <livewire:backend.report.performance.target-setting-component
                            :$month
                            :$store
                            :$selectedCrew
                            :$categories
                            key="{{ $store.'-'.$month.'-'.$selectedCrew }}"
                        />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
