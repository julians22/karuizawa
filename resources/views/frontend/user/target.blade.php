@extends('frontend.layouts.main')

@section('title', __('Target'))

@section('content')
    <x-frontend.sidebar>
        <div class="min-h-full">
            <div class="flex items-center bg-primary-50 pl-14 py-4">
                <div class="font-bold text-2xl text-white uppercase tracking-widest">TARGET & ACTUAL SELLING THIS MONTH</div>
            </div>

            <div class="py-10 container">
                <div style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">
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
                <table class="text-primary-50 font-bold" style="font-size: 1rem;">
                    <tr>
                        <td style="padding: 0 1rem;">
                            Target
                        </td>
                        <td style="padding: 0 1rem;">
                            : {{ price_format($totalTargetAmount) }}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 1rem;">
                            Actual Selling
                        </td>
                        <td style="padding: 0 1rem;">
                            : {{ price_format($totalActualSellingValue) }}
                        </td>
                        <td style="padding: 0 1rem;">|</td>
                        <td>
                            Actual Selling Qty
                        </td>
                        <td style="padding: 0 1rem;">
                            : {{ $totalActualSellingQty }}
                        </td>
                        <td style="padding: 0 1rem;">|</td>
                        <td style="padding: 0 1rem;">
                            Index
                        </td>
                        <td style="padding: 0 1rem;">
                            : @if ($totalTargetAmount > 0)
                                {{ number_format(($totalActualSellingValue / $totalTargetAmount) * 100, 2) }}%
                            @else
                                0%
                            @endif
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
                        <th>Index</th>
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
                            <td>
                                @if ($targetsValue[$key] > 0)
                                    {{ number_format(($actualSellingValue[$key] / $targetsValue[$key]) * 100, 2) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>

                    @endforeach

                </table>
            </div>
        </div>
    </x-frontend.sidebar>
@endsection
