<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function show() {
        return view('pages.laf.locationlist', [
            'locations' => Location::all()
        ]);
    }

    public function edit($id) {
        return view('pages.laf.editlocation', [
            'location' => Location::findOrFail($id)
        ]);
    }

    public function create() {
        return view('pages.laf.createlocation');
    }

    public function update(Request $request, $id) {
        //Validate
        $validated = $request->validate([
            'location_name' => 'required',
            'description' => 'required',
            'visible' => 'required|boolean'
        ], [
            'location_name.required' => 'Et lokasjon navn kreves',
            'description.required' => 'En beskrivelse kreves',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Pull the case
        $location = Location::findOrFail($id);

        $location->update($validated);

        $location->save();

        return redirect('/laf/location')->with(array('message' => "Endringene ble lagret", 'status' => 'success'));
    }

    public function store(Request $request) {
        //Validate
        $validated = $request->validate([
            'location_name' => 'required',
            'description' => 'required',
            'visible' => 'required|boolean'
        ], [
            'location_name.required' => 'Et lokasjon navn kreves',
            'description.required' => 'En beskrivelse kreves',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Create
        $location = Location::create($validated);

        return redirect('/laf/location/' . $location->id)->with(array('message' => "Lokasjonen ble opprettet", 'status' => 'success'));
    }
}
