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
                    <button class="mb-2 btn btn-warning btn-sm" wire:click="generateTargetSettings">Regenerate Target Setting</button>
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

                    <button class="mb-2 btn btn-primary btn-sm" wire:click="generateTargetSettings">Generate Target Setting</button>
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
                            :actualvalue="$target->isSemiCustom() ? $actualSellingVal['Semi Custom'] : ($target->isSemiCustomOuterCategory() ? $actualSellingVal['Semi Custom Outer'] : $actualSellingVal[$target->category->name])"
                            :actualqty="$target->isSemiCustom() ? $actualSellingQty['Semi Custom'] : ($target->isSemiCustomOuterCategory() ? $actualSellingQty['Semi Custom Outer'] : $actualSellingQty[$target->category->name])"
                            :target="$target"
                            :key="$target->id"/>
                    @endforeach
                </table>
            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-3">
                <div class="overflow-hidden card">
                    <div class="d-flex align-items-center p-0 card-body">
                        <div class="bg-primary me-3 p-4 text-white">
                            <i class="cil cil-basket fs-2"></i>
                        </div>
                        <div>
                            <div class="text-primary fs-6 fw-semibold">{{ $totalSellingQty }}</div>
                            <div><small class="font-monospace text-body-secondary text-uppercase fw-semibold">Actual Selling
                                    Qty</small></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="overflow-hidden card">
                    <div class="d-flex align-items-center p-0 card-body">
                        <div class="bg-primary me-3 p-4 text-white">
                            <i class="fas fa-dollar-sign fs-2"></i>
                        </div>
                        <div>
                            <div class="text-primary fs-6 fw-semibold">{{ price_format($totalSellingVal) }}</div>
                            <div><small class="font-monospace text-body-secondary text-uppercase fw-semibold">Actual Selling
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
                    <div class="overflow-hidden card">
                        <div class="d-flex align-items-center p-0 card-body">
                            <div class="bg-primary me-3 p-4 text-white">
                                <i class="fas fa-dollar-sign fs-2"></i>
                            </div>
                            <div>
                                <div class="text-primary fs-6 fw-semibold">{{ price_format($totalTarget) }}</div>
                                <div><small class="font-monospace text-body-secondary text-uppercase fw-semibold">Total Target</small></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3">
                    <div class="overflow-hidden card">
                        <div class="d-flex align-items-center p-0 card-body">
                            <div class="bg-primary me-3 p-4 text-white">
                                <i class="fas fa-percent fs-2"></i>
                            </div>
                            <div>
                                <div class="text-primary fs-6 fw-semibold">{{ $totalIndex }} %</div>
                                <div><small class="font-monospace text-body-secondary text-uppercase fw-semibold">Index total</small></div>
                            </div>
                        </div>
                    </div>
                </div>


            @endif
        </div>


    </div>

</div>
