@extends('frontend.layouts.main')

@section('title', __('Target'))

@section('content')
    <x-frontend.sidebar>
        <div class="min-h-full">
            <div class="flex items-center bg-primary-50 pl-14 py-4">
                <div class="font-bold text-2xl text-white uppercase tracking-widest">TARGET & ACTUAL SELLING THIS MONTH</div>
            </div>

            <div class="py-10 container">
                <div class="mb-10">
                    @php $currentDate = date('Y-m-d'); @endphp
                    Showing your target and actual selling for the month of: &nbsp;&nbsp;
                    <strong
                        style="
                            font-size: 2rem;
                            text-transform: uppercase;
                            font-weight: 700;
                            letter-spacing: 0.1rem;"
                        >
                        {{ date('F Y', strtotime($currentDate)) }}
                    </strong>
                </div>
                <table class="text-2xl text-primary-50 font-bold">
                    <tr>
                        <td>
                            <i class="fa-solid fa-bullseye"></i>
                        </td>
                        <td>
                            Target
                        </td>
                        <td>
                            : {{ price_format($totalTargetAmount) }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-bullseye"></i>
                        </td>
                        <td>
                            Actual Selling
                        </td>
                        <td>
                            : {{ price_format($totalActualSellingValue) }}
                        </td>
                    </tr>
                </table>
            </div>


            <div class="flex items-center bg-primary-50 pl-14 py-4">
                <div class="font-bold text-2xl text-white uppercase tracking-widest">MONTHLY TARGET DETAIL (BREAKDOWN)</div>
            </div>

            <div class="py-10 container">
                <table class="table-target-breakdown">
                    <tr>
                        <th>Category</th>
                        <th>Target</th>
                        <th>Actual Selling</th>
                        <th>Actual Selling Qty</th>
                    </tr>

                    @foreach ($actualSellingValue as $key => $value)

                        <tr>
                            <th>

                                {{ $key }}

                            </th>
                            <td>
                                {{ price_format($targetsValue[$key]) }}
                            </td>
                            <td>
                                {{ price_format($actualSellingValue[$key]) }}
                            </td>
                            <td>
                                {{ $actualSellingQty[$key] }}
                            </td>
                        </tr>

                    @endforeach

                </table>
            </div>
        </div>
    </x-frontend.sidebar>
@endsection
