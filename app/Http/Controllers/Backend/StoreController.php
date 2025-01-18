<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return view('backend.store.index');
    }

    public function create()
    {
        return view('backend.store.create');
    }

    public function edit(Store $store)
    {
        return view('backend.store.edit', compact('store'));
    }

    public function store(Request $request)
    {
        // Validate the request...
        $validated = $request->validate([
            'name' => 'required|unique:stores|max:255',
            'code' => 'required|unique:stores|max:255',
            'accurate_alias' => 'nullable|max:255|unique:stores',
            'address' => 'nullable|max:255',
        ], [
            'name.required' => 'Store Name is required',
            'name.unique' => 'Store Name is already taken',
            'name.max' => 'Store Name should not exceed 255 characters',
            'code.required' => 'Store Code is required',
            'code.unique' => 'Store Code is already taken',
            'code.max' => 'Store Code should not exceed 255 characters',
            'accurate_alias.unique' => 'Store Accurate Alias is already taken',
            'accurate_alias.max' => 'Store Accurate Alias should not exceed 255 characters',
            'address.max' => 'Store Address should not exceed 255 characters',
        ]);

        // Create a new store...
        Store::create($validated);

        return redirect()->route('admin.store.index');
    }

    public function update(Request $request, Store $store)
    {
        // Validate the request...
        $validated = $request->validate([
            'name' => 'required|unique:stores,name,' . $store->id . '|max:255',
            'code' => 'required|unique:stores,code,' . $store->id . '|max:255',
            'address' => 'nullable|max:255',
            'accurate_alias' => 'nullable|max:255|unique:stores,accurate_alias,' . $store->id,
        ], [
            'name.required' => 'Store Name is required',
            'name.unique' => 'Store Name is already taken',
            'name.max' => 'Store Name should not exceed 255 characters',
            'code.required' => 'Store Code is required',
            'code.unique' => 'Store Code is already taken',
            'code.max' => 'Store Code should not exceed 255 characters',
            'accurate_alias.unique' => 'Store Accurate Alias is already taken',
            'accurate_alias.max' => 'Store Accurate Alias should not exceed 255 characters',
            'address.max' => 'Store Address should not exceed 255 characters',
        ]);

        // Update the store...
        $store->update($validated);

        return redirect()->route('admin.store.index');
    }

    public function destroy(Store $store)
    {
        // Delete the store...
        $store->delete();

        return redirect()->route('admin.store.index');
    }

}
