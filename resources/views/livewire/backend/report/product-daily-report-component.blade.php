<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <p>Download report data for: {{ $this->storeModel->name }} - {{ $month }}</p>

    <button
        wire:loading.attr="disabled"
        class="btn btn-primary"
        wire:click='generateReportData'>
        Download Report
    </button>
</div>
