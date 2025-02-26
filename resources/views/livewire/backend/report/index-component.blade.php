<div>
    {{-- The Master doesn't talk, he acts. --}}

    {{-- Show Form select month year --}}
    <div class="row mt-4">

        <div class="col-md-6">
            <form wire:submit.prevent="applyFilter" name="applyFilterForm" class="row g-3">

                <div class="col-md-6">

                    {{-- Select Store --}}
                    <label for="store" class="mb-2 fw-bolder">Select Store</label>
                    <select class="form-control form-control-sm" id="store" wire:model="store">
                        <option value="">Please Select Store</option>
                        @foreach ($storeData as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('store')
                        <small class="text-danger d-inline-block">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    {{-- Select Month --}}
                    <label for="month" class="mb-2 fw-bolder">Select Month</label>
                    <input type="month" class="form-control form-control-sm" id="month" wire:model="month" min="{{$startMonth}}" max="{{$endMonth}}">
                    @error('month')
                        <small class="text-danger d-inline-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Apply Button --}}
                <div class="col-12">
                    <button class="btn btn-primary btn-sm" type="submit">Apply</button>
                </div>
            </form>
        </div>

    </div>

    <div class="row mt-4" >

        <div class="col-md-12">

            @if (!$reportIsReady)
                <div class="alert alert-info" role="alert">
                    <h4 class="alert-heading">You need to select store and month to see the report</h4>
                    <p>Click on the Apply button to see the report</p>
                </div>
            @else
                {{-- Show Report --}}
                <div class="row">

                    {{-- Daily Report CarD --}}

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    Daily Report
                                </h5>
                            </div>

                            <div class="card-body">

                                <livewire:backend.report.product-daily-report-component :$month :$store />

                            </div>
                        </div>
                    </div>

                    {{-- Crew Achievement Report Card --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    Crew Achievement Report
                                </h5>
                            </div>

                            <div class="card-body">

                                {{-- Alert warning for on development --}}
                                <div class="alert alert-warning" role="alert">
                                    This feature is under development
                                </div>

                            </div>
                        </div>
                    </div>



                </div>

            @endif

        </div>


    </div>


</div>
