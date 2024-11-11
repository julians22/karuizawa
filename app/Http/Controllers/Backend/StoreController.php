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

    public function store(Request $request)
    {
        // Validate the request...
        $validated = $request->validate([
            'name' => 'required|unique:stores|max:255',
            'code' => 'required|unique:stores|max:255',
            'address' => 'nullable|max:255',
        ], [
            'name.required' => 'Store Name is required',
            'name.unique' => 'Store Name is already taken',
            'name.max' => 'Store Name should not exceed 255 characters',
            'code.required' => 'Store Code is required',
            'code.unique' => 'Store Code is already taken',
            'code.max' => 'Store Code should not exceed 255 characters',
            'address.max' => 'Store Address should not exceed 255 characters',
        ]);

        // Create a new store...
        Store::create($validated);

        return redirect()->route('admin.store.index');
    }

}
