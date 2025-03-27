<div>
    {{-- The Master doesn't talk, he acts. --}}

    {{-- Show Form select month year --}}
    <div x-data="{ filtertype: @entangle('filtertype') }">

        <div class="row">
            <div class="col-md-3">
                <label for="filtertype" class="mb-2 fw-bolder">Filter Type</label>
                <select name="filtertype" id="filtertype" class="form-control form-control-sm" wire:model.live="filtertype">
                    <option value="monthly">Monthly</option>
                    <option value="daily">Daily</option>
                </select>
            </div>

            <div class="col-md-3" x-show="filtertype === 'monthly'">
                {{-- Select Month --}}
                <label for="month" class="mb-2 fw-bolder">Select Month</label>
                <input type="month" class="form-control form-control-sm" id="month" wire:model.live="month" min="{{$startMonth}}">
                @error('month')
                    <small class="text-danger d-inline-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-3" x-show="filtertype === 'daily'">
                {{-- Select Date --}}
                <label for="date" class="mb-2 fw-bolder">Select Date</label>
                <input type="date" class="form-control form-control-sm" id="date" wire:model.live="date"
                    format="dd"
                    min="{{$startMonth}}" max="{{$endMonth}}">
            </div>
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
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-header">
                                <strong>
                                    Total Selling by Store
                                </strong>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <livewire:backend.chart.store-transaction-chart-component
                                            :$month
                                            key="{{$this->isDaily ? $date : $month}}-store-transaction-chart-component"
                                            :daily="!$this->isDaily ? null : $date"
                                            :reportData="$reportData"
                                            :stores="$stores"
                                            />
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th><span class="h4">Days to Go</span></th>
                                                        <td><strong><span class="h3">{{$this->remaining_days}}</span></strong></td>
                                                    </tr>

                                                    @foreach ($reportData as $store_id => $store)
                                                        <livewire:backend.report.target-calculation-component
                                                            :store="$store_id"
                                                            :month="$month"
                                                            :date="$this->isDaily ? $date : null"
                                                            :remainingDays="$this->remaining_days"
                                                            key="{{$store_id}}-target-calculation-component-{{$month}}-{{$this->remaining_days}}"
                                                            :reportData="$store"
                                                            />
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5">

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
                                        @php
                                            $prefix = $this->isDaily ? $date : $month;
                                            $key = "{$prefix}-{$store->id}-store-monthly-component";
                                        @endphp

                                        <livewire:backend.report.store-monthly-component
                                            :$month
                                            :store="$store->id"
                                            :key="$key"
                                            :reportData="$reportData[$store->id] ?? []"
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
