<div>
    {{-- The whole world belongs to you. --}}
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center">
              <div class="bg-primary text-white p-4 me-3">
                <i class="fas fa-concierge-bell fa-2x"></i>
              </div>
              <div>
                <div class="fs-6 fw-semibold text-primary">{{ price_format($sumTarget) }}</div>
                <div class="text-body-secondary text-uppercase fw-semibold small">Total Target</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center">
              <div class="bg-primary text-white p-4 me-3">
                <i class="fas fa-dollar-sign fa-2x"></i>
              </div>
              <div>
                <div class="fs-6 fw-semibold text-primary">{{ price_format($actualSell) }}</div>
                <div class="text-body-secondary text-uppercase fw-semibold small">Input Selling</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center">
              <div class="bg-primary text-white p-4 me-3">
                <i class="fas fa-dollar-sign fa-2x"></i>
              </div>
              <div>
                <div class="fs-6 fw-semibold text-primary">{{ price_format($allActualSell) }}</div>
                <div class="text-body-secondary text-uppercase fw-semibold small">Total Actual Selling</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="card overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center">
              <div class="bg-primary text-white p-4 me-3">
                <i class="fas fa-percent fa-2x"></i>
              </div>
              <div>
                <div class="fs-6 fw-semibold text-primary">{{ $percent }}%</div>
                <div class="text-body-secondary text-uppercase fw-semibold small">Index</div>
              </div>
            </div>
          </div>
        </div>

      </div>

</div>
