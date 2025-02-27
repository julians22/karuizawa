<div>
    {{-- The Master doesn't talk, he acts. --}}

    {{-- Show Form select month year --}}
    <div class="row">

        <div class="col-md-3">
            {{-- Select Month --}}
            <label for="month" class="mb-2 fw-bolder">Select Month</label>
            <input type="month" class="form-control form-control-sm" id="month" wire:model.live="month" min="{{$startMonth}}" max="{{$endMonth}}">
            @error('month')
                <small class="text-danger d-inline-block">{{ $message }}</small>
            @enderror
        </div>

    </div>

    <div class="row mt-4" >

        <div class="col-md-12">

            @if (!$this->reportIsReady)
                <div class="alert alert-info" role="alert">
                    <p><strong>You need to select month to see the report</strong></p>
                </div>
            @else
                {{-- Show Report --}}
                <div class="row g-3">

                    {{-- Daily Report CarD --}}
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <strong>
                                    Daily Report
                                </strong>
                            </div>

                            <div class="card-body">

                                <livewire:backend.report.product-daily-report-component :$month />

                            </div>
                        </div>
                    </div>

                    {{-- Crew Achievement Report Card --}}
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <strong>
                                    Crew Achievement Report
                                </strong>
                            </div>

                            <div class="card-body">

                                {{-- Alert warning for on development --}}
                                <div class="alert alert-warning" role="alert">
                                    This feature is under development
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Store Transaction Chart --}}
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <strong>
                                    Total Seeling by Store
                                </strong>
                            </div>

                            <div class="card-body">

                                {{-- /backend\chart\store-transaction-chart-component --}}
                                <livewire:backend.chart.store-transaction-chart-component :$month/>

                            </div>
                        </div>
                    </div>



                </div>

            @endif

        </div>


    </div>


</div>
