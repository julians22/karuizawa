<?php

namespace App\Http\Controllers\Crew;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index() {
        return view('frontend.user.dashboard');
    }

    public function target() {

        $categories = Category::all();

        $crew = auth()->user();

        $actualSellingValue = [];
        $actualSellingQty = [];

        $targetsValue = [];

        foreach ($categories as $value) {
            $actualSellingValue[$value->name] = 0;
            $actualSellingQty[$value->name] = 0;
            $targetsValue[$value->name] = 0;
        }

        $actualSellingValue['Semi Custom'] = 0;
        $actualSellingQty['Semi Custom'] = 0;


        $totalActualSellingValue = 0;
        $totalActualSellingQty = 0;

        $totalTargetAmount = 0;


        $targets = $crew->targetSettings()
            ->with(['category'])
            ->where('month', date('Y-m'))
            ->where('store_id', $crew->store_id)
            ->get();

        if ($targets->count() > 0) {
            foreach ($targets as $target) {
                $totalTargetAmount += $target->target;

                if (!$target->isSemiCustom()) {
                    $targetsValue[$target->category->name] = $target->target;
                }else {
                    $targetsValue['Semi Custom'] = $target->target;
                }

            }
        }

        $readyToWear = $this->getReadtoWear($crew->id, $crew->store_id, date('m'), date('Y'));
        if ($readyToWear->count() > 0) {
            foreach ($readyToWear as $item) {
                $actualSellingQty[$item->product_rtw->category->name] += $item->quantity;
                $actualSellingValue[$item->product_rtw->category->name] += $item->total_price;
            }
            $totalActualSellingQty = collect($actualSellingQty)->sum();
            $totalActualSellingValue = collect($actualSellingValue)->sum();
            $totalTargetAmount += $totalActualSellingValue;
        }

        $semiCustom = $this->getSemiCustom($crew->id, $crew->store_id, date('m'), date('Y'));
        if ($semiCustom->count() > 0) {
            foreach ($semiCustom as $item) {
                $actualSellingQty['Semi Custom'] += $item->quantity;
                $actualSellingValue['Semi Custom'] += $item->price;
            }
            $totalActualSellingQty = collect($actualSellingQty)->sum();
            $totalActualSellingValue = collect($actualSellingValue)->sum();
            $totalTargetAmount += $totalActualSellingValue;
        }


        return view('frontend.user.target', compact('targets', 'totalTargetAmount', 'totalActualSellingValue', 'totalActualSellingQty', 'actualSellingValue', 'actualSellingQty', 'targetsValue'));
    }

    protected function getReadtoWear($crewId, $store, $month, $year)
    {
        $trans = OrderItem::with([
            'order',
            'product_rtw',
            'product_rtw.category',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year, $crewId) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'))
                    ->where('user_id', $crewId);
            })
            ->readyToWear()
            ->get();


        return $trans;
    }

    protected function getSemiCustom($crewId, $store, $month, $year)
    {
        $trans = OrderItem::with([
            'order',
            'product_sc',
            'order.user' => function ($query) {
                $query->withTrashed();
            }
            ])
            ->whereHas('order', function ($query) use ($store, $month, $year, $crewId) {
                return $query->whereMonth('order_date', $month)
                    ->whereYear('order_date', $year)
                    ->where('store_id', $store)
                    ->where('status', config('enums.order_status.completed'))
                    ->where('user_id', $crewId);
            })
            ->semiCustom()
            ->get();

        return $trans;
    }
}
