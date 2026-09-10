<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\StoreCustomerAddressRequest;
use App\Http\Requests\Api\Customer\UpdateCustomerAddressRequest;
use App\Http\Resources\CustomerAddressResource;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->latest()->get();

        return response()->json([
            'success' => true,
            'data' => CustomerAddressResource::collection($addresses),
        ]);
    }

    public function store(StoreCustomerAddressRequest $request)
    {
        $customer = $request->user();
        $data = $request->validated();

        $address = DB::transaction(function () use ($customer, $data) {
            if (!empty($data['is_primary']) || !$customer->addresses()->exists()) {
                $customer->addresses()->update(['is_primary' => false]);
                $data['is_primary'] = true;
            }

            return $customer->addresses()->create($data);
        });

        return response()->json([
            'success' => true,
            'data' => new CustomerAddressResource($address),
        ], 201);
    }

    public function show(Request $request, CustomerAddress $address)
    {
        $this->authorizeOwnership($request, $address);

        return response()->json([
            'success' => true,
            'data' => new CustomerAddressResource($address),
        ]);
    }

    public function update(UpdateCustomerAddressRequest $request, CustomerAddress $address)
    {
        $this->authorizeOwnership($request, $address);

        $customer = $request->user();
        $data = $request->validated();

        DB::transaction(function () use ($customer, $address, $data) {
            if (!empty($data['is_primary'])) {
                $customer->addresses()->where('id', '!=', $address->id)->update(['is_primary' => false]);
            }

            $address->update($data);
        });

        return response()->json([
            'success' => true,
            'data' => new CustomerAddressResource($address->fresh()),
        ]);
    }

    public function destroy(Request $request, CustomerAddress $address)
    {
        $this->authorizeOwnership($request, $address);

        $wasPrimary = $address->is_primary;
        $customer = $request->user();
        $address->delete();

        if ($wasPrimary) {
            $customer->addresses()->oldest()->first()?->update(['is_primary' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus.',
        ]);
    }

    public function setPrimary(Request $request, CustomerAddress $address)
    {
        $this->authorizeOwnership($request, $address);

        $customer = $request->user();

        DB::transaction(function () use ($customer, $address) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_primary' => false]);
            $address->update(['is_primary' => true]);
        });

        return response()->json([
            'success' => true,
            'data' => new CustomerAddressResource($address->fresh()),
        ]);
    }

    private function authorizeOwnership(Request $request, CustomerAddress $address): void
    {
        abort_unless($address->customer_id === $request->user()->id, 403, 'Alamat tidak ditemukan.');
    }
}
