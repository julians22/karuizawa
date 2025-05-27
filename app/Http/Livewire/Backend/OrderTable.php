<?php

namespace App\Http\Livewire\Backend;

use App\Jobs\Accurate\InvoiceCreateDownPayment;
use App\Jobs\Accurate\InvoiceFinishDownPayment;
use App\Jobs\Accurate\InvoiceSave;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class OrderTable extends DataTableComponent
{
    protected $model = Order::class;

    public $stores = [
        '' => 'All',
    ];

    public $allowBulkActions = true;

    function mount($allowBulkActions = true){

        $this->allowBulkActions = $allowBulkActions;

        $stores = Store::all();

        foreach ($stores as $store) {
            $this->stores[$store->id] = $store->code;
        }

    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setBulkActions([
            'syncAccurate' => 'Sync to Accurate',
            'finishDownPayment' => 'Finish Down Payment',
        ]);
        $this->setHideBulkActionsWhenEmptyEnabled();
        $this->setBulkActionConfirms([
            'syncAccurate',
            'finishDownPayment',
        ]);

        $this->setBulkActionConfirmMessage('syncAccurate', 'Are you sure you want to sync the selected orders to Accurate?');
        $this->setBulkActionConfirmMessage('finishDownPayment', 'Are you sure you want to finish the down payment for the selected orders?');

        $this->setBulkActionsStatus($this->allowBulkActions);

        $this->setClearSelectedOnSearch(true);

        $this->setSingleSortingDisabled();
        $this->setDefaultSort('created_at', 'desc');

        $this->addAdditionalSelects('status');

        $this->setSearchPlaceholder('Search by Order ID, Customer Name, Store Code, Crew Name');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'cancel' => 'Cancel',
                ])
                ->filter(fn($builder, $value) => $builder->where('status', $value)),
            SelectFilter::make('Store')
                ->options(
                    $this->stores
                )
                ->filter(fn($builder, $value) => $builder->where('orders.store_id', $value)),
            DateFilter::make('Order Date')
                ->filter(fn($builder, $value) => $builder->whereDate('order_date', $value)),
            SelectFilter::make('Sync Status')
                ->options([
                    '' => 'All',
                    'synced' => 'Synced',
                    'not_synced' => 'Not Synced',
                ])
                ->filter(
                    function($builder, $value){
                        if ($value === 'synced') {
                            return $builder->whereNotNull('accurate_sync_date');
                        }

                        if ($value === 'not_synced') {
                            return $builder->whereNull('accurate_sync_date');
                        }
                    }
                ),
            SelectFilter::make('Order Type')
                ->options([
                    '' => 'All',
                    'sc' => 'Semi Custom',
                    'rtw' => 'Only Ready to Wear',
                ])
                ->filter(function($builder, $value){
                    if ($value === 'sc') {
                        return $builder->whereHas('orderItems', function($query){
                            $query->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                    }

                    if ($value === 'rtw') {
                        return $builder->whereDoesntHave('orderItems', function($query){
                            $query->where('product_type', 'App\Models\SemiCustomProduct');
                        });
                    }
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Order Id", "order_number")
                ->deselected()
                ->searchable()
                ->sortable(),
            Column::make("Customer", "customer.full_name")
                ->searchable()
                ->sortable(),
            Column::make("Store", "store.code")
                ->sortable(),
            Column::make('Crew', 'user.name')
                ->searchable()
                ->sortable(),
            Column::make("Total price", "total_price")
                ->sortable()
                ->format(fn($value) => price_format($value)),
            Column::make("Status", "status")
                ->sortable()
                ->label(
                    function($row, Column $column){
                        switch ($row->status) {
                            case 'pending':
                                return '<span class="bg-warning badge">Pending</span>';
                            case 'completed':
                                return '<span class="bg-success badge">Completed</span>';
                            case 'cancelled':
                                return '<span class="bg-danger badge">Canceled</span>';
                            default:
                                return '<span class="bg-secondary badge">Unknown</span>';
                        }
                    }
                )
                ->html(),
            BooleanColumn::make("Semi Custom?", "id")
                ->setCallback(function(string $value, $row) {
                    return $row->hasSemiCustom();
                }),
            Column::make('Semi Custom Status')
                ->label(fn($row) => view('backend.order.includes.semi_custom_status', ['order' => $row])),
            Column::make('Accurate Sync', 'accurate_sync_date')
                ->format(fn($value, $row) => $value ? $value : "Not Synced"),
            Column::make("Created at", "created_at")
                ->format(fn($value) => $value->diffForHumans())
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->format(fn($value) => $value->diffForHumans())
                ->sortable(),
            Column::make('Order Date', 'order_date')
                ->sortable(),
            Column::make("Actions")
                ->label(fn($row, Column $column) => view('backend.order.includes.actions', ['order' => $row])),
        ];
    }

    public function syncAccurate(){

        $this->dispatch('client-loading'); // show loading spinner

        // check if order_date, status, is applied to the selected orders

        $rows = $this->getSelectedRows();

        $target = [];

        foreach ($rows as $id) {
            $order = Order::where('accurate_sync_date', null)->find($id);
            if (!$order || $order->status !== 'completed') {
                continue;
            }
            $order->load('orderItems.product', 'store', 'customer');

            $autoNumber = 55;

            if ($order->store->code !== null) {
                if ($order->store->code == 'PIK') {
                    $autoNumber = config('accurate.trans_no.PIK');
                }

                if ($order->store->code == 'AST') {
                    $autoNumber = config('accurate.trans_no.AST');
                }
            }

            $customerNo = config('accurate.customer_list.AST');
            if ($order->store->code !== null) {
                $customerNo = config('accurate.customer_list.' . $order->store->code);
            }

            $warehouseName = config('accurate.warehouse_list.AST');
            if ($order->store->code !== null) {
                $warehouseName = config('accurate.warehouse_list.' . $order->store->code);
            }

            $target[$id] = [
                'customerNo' => $customerNo,
                'transDate' => $order->order_date ? $order->order_date->format('d/m/Y') : $order->created_at->format('d/m/Y'),
                'currencyCode' => 'IDR',
                'description' => $order->order_number,
                'documentCode' => 'DIGUNGGUNG',
                'taxable' => true,
                'inclusiveTax' => true,
                'typeAutoNumber' => $autoNumber,
                'types' => [],
            ];

            $orderItems = $order->orderItems;

            foreach ($orderItems as $orderItem) {

                if ($orderItem->isSemiCustom()) {

                    $target[$id]['types'][$orderItem->type][] = [
                        "price" => $orderItem->price,
                        "quantity" => $orderItem->quantity,
                    ];
                }

                if ($orderItem->isReadyToWear()) {
                    $target[$id]['types'][$orderItem->type][] = [
                        'itemNo' => $orderItem->product->sku,
                        'unitPrice' => $orderItem->price,
                        'itemDiscPercent' => $orderItem->discount_percentage,
                        'detailName' => $orderItem->product->product_name,
                        'quantity' => $orderItem->quantity,
                        'departmentName' => 'Apparel',
                        'warehouseName' => $warehouseName,
                    ];
                }
            }

            $order->accurate_sync_date = now();
            $order->save();
        }

        if (count($target) === 0) {
            $this->dispatch('destroy-alert'); // hide loading spinner
            $this->dispatch('alert', message: 'No orders to sync to Accurate, Please Select Orders that are not synced yet', useSwal: true, type: 'warning');
            return;
        }

        $downPaymentJob = [];
        $readyToWearJob = [];

        $collection = collect($target);

        $collection->each(function($order, $id) use (&$downPaymentJob, &$readyToWearJob) {

            $dataPass = [
                'customerNo' => $order['customerNo'],
                'transDate' => $order['transDate'],
                'currencyCode' => $order['currencyCode'],
                'documentCode' => $order['documentCode'],
                'taxable' => $order['taxable'],
                'isTaxable' => $order['taxable'],
                'inclusiveTax' => $order['inclusiveTax'],
                'typeAutoNumber' => $order['typeAutoNumber'],
                'description' => $order['description'],
            ];

            collect($order['types'])->map(function($type, $key) use ($dataPass, &$downPaymentJob, &$readyToWearJob, $id) {

                if ($key === 'SC') {
                    $dataPass['dpAmount'] = 0;
                    $dataPass['description'] = 'DP Semi Custom MTM WEB ORDER: ' . $dataPass['description'];

                    foreach ($type as $item) {
                        $priceResult = $item['price'] * $item['quantity'];
                        $dataPass['dpAmount'] += $priceResult;
                    }
                    $dataPass['dpAmount'] = number_format($dataPass['dpAmount'], 2, '.', '');

                    $downPaymentJob[] = new InvoiceCreateDownPayment($dataPass, $id);
                }

                if ($key === 'RTW') {
                    $dataPass['detailItem'] = $type;
                    $dataPass['description'] = 'RTW WEB ORDER: ' . $dataPass['description'];

                    $readyToWearJob[] = new InvoiceSave($dataPass);
                }
            });
        });

        if (count($downPaymentJob) > 0) {
            $this->batchTheJob($downPaymentJob, 'Down Payment');
        }

        if (count($readyToWearJob) > 0) {
            $this->batchTheJob($readyToWearJob, 'Ready to Wear');
        }

        $this->dispatch('destroy-alert'); // hide loading spinner
        $this->dispatch('alert', message: 'Orders are being synced to Accurate, please wait for the process to complete', useSwal: true, type: 'success');

        $this->clearSelected();
    }

    public function finishDownPayment(){
        $this->dispatch('client-loading');

        $rows = $this->getSelectedRows();

        $target = [];

        $target['PIK'] = [];
        $target['AST'] = [];

        foreach ($rows as $id) {

            $order = Order::find($id);

            // should be semi custom
            if (!$order || !$order->hasSemiCustom()) {
                continue;
            }

            $order->load('orderItems.product', 'store', 'customer', 'downPaymentResponse');

            // check if order is has down payment response
            if (!$order->has('downPaymentResponse')) {
                continue;
            }

            // check if order.orderItems.product is all finished
            $orderItems = $order->orderItems;
            $allFinished = true;

            foreach ($orderItems as $orderItem) {
                if ($orderItem->product_sc->isFinish() === false) {
                    $allFinished = false;
                    break;
                }
            }

            if ($allFinished === false) {
                continue;
            }

            $orderItems = $order->orderItems;

            foreach ($orderItems as $orderItem) {

                if ($orderItem->isSemiCustom()) {
                    $target[$order->store->code]['detailItem'][] = [
                        'itemNo' => $orderItem->product_sc->code,
                        'detailName' => $orderItem->product_sc->name,
                        'unitPrice' => $orderItem->price,
                        'quantity' => $orderItem->quantity,
                        'departmentName' => 'Apparel',
                        'warehouseName' => $order->store->code === 'PIK' ? config('accurate.warehouse_list.PIK') : config('accurate.warehouse_list.AST'),
                    ];
                }
            }
            $target[$order->store->code]['detailDownPayment'][] = [
                'invoiceNumber' => $order->downPaymentResponse->down_payment_number,
                'paymentAmount' => $order->downPaymentResponse->down_payment_amount,
            ];

            $target[$order->store->code]['orderId'][] = $id;
        }


        $dataPass = [];

        $collection = collect($target);
        foreach ($collection as $key => $orderValue) {

            $autoNumber = 55;
            if ($key === 'PIK') {
                $autoNumber = config('accurate.trans_no.PIK');
            } elseif ($key === 'AST') {
                $autoNumber = config('accurate.trans_no.AST');
            }

            $dataPass[$key] = [
                'customerNo' => config('accurate.customer_list.' . $key),
                'detailItem' => $orderValue['detailItem'] ?? [],
                'detailDownPayment' => $orderValue['detailDownPayment'] ?? [],
                'transDate' => now()->format('d/m/Y'),
                'currencyCode' => 'IDR',
                'documentCode' => 'DIGUNGGUNG',
                'taxable' => true,
                'inclusiveTax' => true,
                'typeAutoNumber' => $autoNumber,
                'description' => 'Finish Down Payment for ' . $key . ' Orders',
            ];
        }

        $finishDownPaymentJob = [];

        foreach ($dataPass as $key => $params) {
            if (count($params['detailItem']) > 0) {
                $finishDownPaymentJob[] = new InvoiceSave($params);
            }
        }

        $this->batchTheJob($finishDownPaymentJob, 'Finish Down Payment');

        $this->dispatch('destroy-alert'); // hide loading spinner
        $this->dispatch('alert', message: 'Finish Down Payment is being synced to Accurate, please wait for the process to complete', useSwal: true, type: 'success');
        $this->clearSelected();
    }

    protected function batchTheJob($jobs, $type){
        Bus::batch($jobs)
            ->onQueue('default')
            ->before(function (Batch $batch) use ($type) {
                activity()
                    ->causedBy(auth()->user())
                    ->log('Syncing Order '. $type .' to Accurate, ' . $batch->totalJobs . ' jobs will be processed on batch id ' . $batch->id);
            })
            ->catch(function (Batch $batch, $e) use ($type) {
                // First batch job failure...
                activity()
                    ->causedBy(auth()->user())
                    ->log('Syncing Order '. $type .' to Accurate failed, on batch id ' . $batch->id . ' with error ' . $e->getMessage());
            })
            ->finally(function (Batch $batch) use ($type) {
                // All batch jobs completed...
                activity()
                    ->causedBy(auth()->user())
                    ->log('Syncing Order '. $type .' to Accurate completed, ' . $batch->totalJobs . ' jobs processed on batch id ' . $batch->id);
            })
            ->dispatch();
    }
}
