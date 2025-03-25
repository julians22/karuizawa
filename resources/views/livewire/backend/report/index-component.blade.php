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

                                <livewire:backend.report.product-daily-report-component :$month :$stores />

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

                                <livewire:backend.report.category-value-report-component :$month :$stores />

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

                                <div class="row">
                                    <div class="col-md-8">
                                        {{-- /backend\chart\store-transaction-chart-component --}}
                                        <livewire:backend.chart.store-transaction-chart-component :$month />
                                    </div>

                                    <div class="col-md-4">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">

                        @foreach ($stores as $store)
                            <div
                                class="card {{$loop->last ? 'mt-3' : ''}}">
                                <div class="card-header">
                                    <strong>
                                        {{$store->name}}
                                    </strong>
                                    <small>Breakdown each Product Category</small>
                                </div>

                                <div class="card-body">

                                    @if (!$this->reportIsReady)
                                        <div class="alert alert-info" role="alert">
                                            <p><strong>You need to select month to see the report</strong></p>
                                        </div>
                                    @else
                                        <livewire:backend.report.store-monthly-component :$month :$store
                                            key="{{$month}}-{{$store->id}}-store-monthly-component"
                                            />
                                    @endif


                                </div>
                            </div>

                        @endforeach
                    </div>



                </div>

            @endif

        </div>


    </div>


</div>
