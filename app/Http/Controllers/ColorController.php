<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function show() {
        return view('pages.laf.colorlist', [
            'colors' => Color::all()
        ]);
    }

    public function edit($id) {
        return view('pages.laf.editcolor', [
            'color' => Color::findOrFail($id)
        ]);
    }

    public function create() {
        return view('pages.laf.createcolor');
    }

    public function update(Request $request, $id) {
        //Validate
        $validated = $request->validate([
            'color' => 'required',
            'class' => 'required_without:colorcode|nullable',
            'colorcode' => 'required_without:class|nullable',
            'visible' => 'required|boolean'
        ], [
            'item.required' => 'Et fargenavn kreves',
            'class.required_without' => 'En fargeklasse kreves om ingen fargekode gis',
            'colorcode.required_without' => 'En fargekode kreves om ingen fargeklasse gis',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Pull the case
        $color = Color::findOrFail($id);

        $color->update($validated);

        $color->save();

        return redirect('/laf/color')->with(array('message' => "Endringene ble lagret", 'status' => 'success'));
    }

    public function store(Request $request) {
        //Validate
        $validated = $request->validate([
            'color' => 'required',
            'class' => 'required_without:colorcode|nullable',
            'colorcode' => 'required_without:class|nullable',
            'visible' => 'required|boolean'
        ], [
            'item.required' => 'Et fargenavn kreves',
            'class.required_without' => 'En fargeklasse kreves om ingen fargekode gis',
            'colorcode.required_without' => 'En fargekode kreves om ingen fargeklasse gis',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Create
        $color = Color::create($validated);

        return redirect('/laf/color/' . $color->id)->with(array('message' => "Fargen ble opprettet", 'status' => 'success'));
    }
}
