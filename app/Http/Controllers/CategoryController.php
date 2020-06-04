<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show() {
        return view('pages.laf.categorylist', [
            'categories' => Category::all()
        ]);
    }

    public function edit($id) {
        return view('pages.laf.editcategory', [
            'category' => Category::findOrFail($id)
        ]);
    }

    public function create() {
        return view('pages.laf.createcategory');
    }

    public function update(Request $request, $id) {
        //Validate
        $validated = $request->validate([
            'category_name' => 'required',
            'description' => 'required',
            'visible' => 'required|boolean'
        ], [
            'item.required' => 'Et kategori navn kreves',
            'description.required' => 'En beskrivelse kreves',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Pull the case
        $category = Category::findOrFail($id);

        $category->update($validated);

        $category->save();

        return redirect('/laf/category')->with(array('message' => "Endringene ble lagret", 'status' => 'success'));
    }

    public function store(Request $request) {
        //Validate
        $validated = $request->validate([
            'category_name' => 'required',
            'description' => 'required',
            'visible' => 'required|boolean'
        ], [
            'item.required' => 'Et kategori navn kreves',
            'description.required' => 'En beskrivelse kreves',
            'visible.required' => 'En synlig status kreves',
            'visible.boolean' => 'Synlig status må være en boolean (Ja/Nei)'
        ]);

        //Create
        $category = Category::create($validated);

        return redirect('/laf/category/' . $category->id)->with(array('message' => "Kategorien ble opprettet", 'status' => 'success'));
    }
}
