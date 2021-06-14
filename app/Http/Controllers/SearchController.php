<?php

namespace App\Http\Controllers;

use App\FuzzySearch;
use App\Models\Investigation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function do_search(Request $request) {
        if (empty($request->input('query'))) {
            $query = "";
        } else {
            $query = $request->input('query');
        }
        $search = Investigation::search($query)
            ->paginate(25);

        //dd($search);

        return view('pages.laf.listsearch', [
            'query' => $request->input('query'),
            'title' => 'Søkeresultat; ' . $query,
            'cases' => $search
        ]);

    }

    public function item_search(Request $request) {
        if (empty($request->input('query'))) {
            $query = "";
        } else {
            $query = $request->input('query');
        }
        $search = Investigation::search($query)
            ->paginate(5);
        //->buildPayload();
            //->get();

        //(dd($search);
        return json_encode($search);
    }

    public function show_results(Request $request) {
        $validated = $request->validate([
            'data' => 'required|array'
        ], [
            'data.required' => 'Dataen som skal vises kreves',
            'data.array' => 'Mangler filer',
        ]);

        //dd($validated);

        return view('panels.laf.search', [
            'data' => $validated["data"],
        ]);
    }
}
