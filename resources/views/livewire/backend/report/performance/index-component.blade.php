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

    <div class="card mt-4">
        <div class="card-header">
            Crew Target Setup
        </div>
        <div class="card-body">
            <div class="row" x-data="{ isCreateOpen: @entangle('isCreate') }">
                <div class="col-md-12">
                    <button x-show="!isCreateOpen" class="btn btn-sm btn-primary mb-2" wire:click="createTarget">Attach New Target</button>
                </div>
            </div>
            <div x-data="{ isCreateOpen: @entangle('isCreate') }">
                <div x-show="isCreateOpen">
                        @if ($isCreate)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <livewire:backend.report.performance.form.create
                                            :$categories
                                            :$month
                                            :$store/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                </div>

                <div x-data="{ isEditOpen: @entangle('isEdit'), isCreateOpen: @entangle('isCreate') }">
                    <div x-show="isEditOpen">
                        @if (!empty($editTargetId) && $isEdit)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                    <h6 class="card-title mb-2">Edit Target</h6>
                                    <livewire:backend.report.performance.form.edit
                                        :targetId="$editTargetId" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <div class="list-group" id="list-tab" role="tablist">
                        @foreach ($categories as $category)
                            <a
                                wire:key="tab-list-{{$category->id}}"
                                wire:ignore
                                class="list-group-item list-group-item-action {{$loop->first ? 'active' : ''}}"
                                id="list-{{$category->id}}-list"
                                data-coreui-toggle="list"
                                href="#list-{{$category->id}}"
                                role="tab"
                                aria-controls="{{$category->name}}">
                                {{$category->name}}
                            </a>
                        @endforeach
                        <a
                            wire:key="tab-list-semicustom"
                            wire:ignore
                            class="list-group-item list-group-item-action"
                            id="list-semicustom-list"
                            data-coreui-toggle="list"
                            href="#list-semicustom"
                            role="tab"
                            aria-controls="Semi Custom">
                            Semi Custom
                        </a>
                    </div>
                </div>
                <div class="col-10">
                    <div class="tab-content" id="nav-tabContent">

                        @foreach ($categories as $categoryTabContent)

                            <div
                                wire:key="tab-content-{{$category->id}}"
                                class="tab-pane fade {{$loop->first ? 'show active' : ''}}" id="list-{{$categoryTabContent->id}}" role="tabpanel" aria-labelledby="list-{{$categoryTabContent->id}}-list" wire:ignore.self>
                                <livewire:backend.report.performance.target-setting-component
                                    :$month
                                    :category="$categoryTabContent"
                                    :$store
                                    :key="'target-setting-component-'.$categoryTabContent->id.'-'.$store.'-'.$month"
                                />

                                <livewire:backend.report.performance.result.index-report
                                    :category="$categoryTabContent"
                                    :month="$month"
                                    :store="$store"
                                    :key="'result-index-report-'.$categoryTabContent->id.'-'.$store.'-'.$month"
                                    />
                            </div>

                        @endforeach

                        <div
                            wire:key="tab-content-semicustom"
                            class="tab-pane fade" id="list-semicustom" role="tabpanel" aria-labelledby="list-semicustom-list" wire:ignore.self>
                            <livewire:backend.report.performance.target-setting-semi-custom-component
                                :$month
                                :category="'semicustom'"
                                :$store
                                :key="'target-setting-component-semicustom-'.$store.'-'.$month"
                            />

                            <livewire:backend.report.performance.result.semi-custom
                                :category="'semicustom'"
                                :month="$month"
                                :store="$store"
                                :key="'result-semicustom-'.$store.'-'.$month"
                                />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>

</div>
