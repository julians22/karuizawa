<div class="row">

    <div class="col-md-12">

        <div class="card-title">
            @lang('Performance Report') for <strong>{{$this->crew->name}}</strong>
        </div>

        <div class="row">

            <div class="col-md-12">
                @if ($this->crew->haveTargetSettings())
                    <div class="alert alert-warning" role="alert">
                        <div>
                            You can regenerate the target setting for this crew. this can be regenerated if you have update your product list, category list or any other settings.
                        </div>
                    </div>
                    <button class="btn btn-warning btn-sm mb-2" wire:click="generateTargetSettings">Regenerate Target Setting</button>
                @else
                    <div class="alert alert-warning" role="alert">
                        <div>
                            <strong>
                                Target Setting has not generated yet for this crew.
                            </strong>
                        </div>
                        <small>
                            Please generate the target setting to view the performance report.
                        </small>
                    </div>

                    <button class="btn btn-primary btn-sm mb-2" wire:click="generateTargetSettings">Generate Target Setting</button>
                @endif
                <table class="table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Target</th>
                            <th>Actual (QTY)</th>
                            <th>Actual (Value)</th>
                            <th>Index</th>
                        </tr>
                    </thead>
                    @foreach ($this->crew_target as $target)
                        <livewire:backend.report.performance.target-item-component
                            :actualvalue="$target->isSemiCustom() ? $actualSellingVal['Semi Custom'] : $actualSellingVal[$target->category->name]"
                            :actualqty="$target->isSemiCustom() ? $actualSellingQty['Semi Custom'] : $actualSellingQty[$target->category->name]"
                            :target="$target"
                            :key="$target->id"/>
                    @endforeach
                </table>
            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-primary text-white p-4 me-3">
                            <i class="cil cil-basket fs-2"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold text-primary">{{ $totalSellingQty }}</div>
                            <div><small class="text-body-secondary text-uppercase fw-semibold font-monospace">Actual Selling
                                    Qty</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="bg-primary text-white p-4 me-3">
                            <i class="fas fa-dollar-sign fs-2"></i>
                        </div>
                        <div>
                            <div class="fs-6 fw-semibold text-primary">{{ price_format($totalSellingVal) }}</div>
                            <div><small class="text-body-secondary text-uppercase fw-semibold font-monospace">Actual Selling
                                    Value</small></div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($this->crew->haveTargetSettings())
                @php

                    $totalTarget = collect($this->crew_target)->sum('target');
                    $totalActualValue = $totalSellingVal;

                    if ($totalTarget == 0) {
                        $totalIndex = 0;
                    }else{
                        $totalIndex = round(($totalActualValue / $totalTarget) * 100, 2);
                    }
                @endphp

                <div class="col-12 col-md-3">
                    <div class="card overflow-hidden">
                        <div class="card-body p-0 d-flex align-items-center">
                            <div class="bg-primary text-white p-4 me-3">
                                <i class="fas fa-dollar-sign fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-6 fw-semibold text-primary">{{ price_format($totalTarget) }}</div>
                                <div><small class="text-body-secondary text-uppercase fw-semibold font-monospace">Total Target</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="card overflow-hidden">
                        <div class="card-body p-0 d-flex align-items-center">
                            <div class="bg-primary text-white p-4 me-3">
                                <i class="fas fa-percent fs-2"></i>
                            </div>
                            <div>
                                <div class="fs-6 fw-semibold text-primary">{{ $totalIndex }} %</div>
                                <div><small class="text-body-secondary text-uppercase fw-semibold font-monospace">Index total</small></div>
                            </div>
                        </div>
                    </div>
                </div>


            @endif
        </div>


    </div>

</div>
