<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoControler extends Controller
{
    public function index()
    {
        return view('backend.promo.index');
    }

    public function create()
    {
        return view('backend.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        Promo::create($request->all());

        return redirect()->route('admin.promo.index')->with('success', 'Promo created successfully.');
    }

    public function edit(Promo $promo)
    {
        return view('backend.promo.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        $promo->update($request->all());

        return redirect()->route('admin.promo.index')->with('success', 'Promo updated successfully.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promo.index')->with('success', 'Promo deleted successfully.');
    }

}
