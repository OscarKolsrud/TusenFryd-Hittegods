<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSMS;
use App\Mail\NewLostNotification;
use App\Models\Category;
use App\Models\Color;
use App\Models\Conversation;
use App\Models\Investigation;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Sebdesign\SM\Facade as StateMachine;
use SM\SMException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InvestigationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, $view="all", $list="found", $date="none")
    {
        //This is just to get a statemachine object to get the title of the wanted status
        $object = Investigation::where('status', $list)->first();

        if (isset($object->reference)) {
            $sm = StateMachine::get($object, 'investigation');

            if ($date == "none" && $view == "all") {
                return view('pages.laf.listpaginated', [
                    'title' => $sm->metadata('state', $list, 'title'),
                    'cases' => Investigation::where('status', $list)->paginate(25)
                ]);
            } elseif($view !== "all") {
                return view('pages.laf.listpaginated', [
                    'title' => $sm->metadata('state', $list, 'title'),
                    'cases' => Investigation::where('status', $list)->where('initial_status', $view)->paginate(25)
                ]);
            } else {
                return view('pages.laf.listpaginated', [
                    'title' => $sm->metadata('state', $list, 'title'),
                    'cases' => Investigation::where('status', $list)->where('lost_date', $date)->paginate(25)
                ]);
            }
        } else {
            return view('pages.laf.listpaginated', [
                'title' => 'Resultat',
                'cases' => Investigation::where('status', $list)->paginate(25)
            ]);
        }
    }


    /**
     * Show the form for creating a new resource. (Item)
     *
     * @return \Illuminate\Http\Response
     */
    public function create_item()
    {
        return view('pages.laf.item.create', [
            'categories' => Category::where('visible', 1)->get(),
            'colors' => Color::where('visible', 1)->get(),
            'locations' => Location::where('visible', 1)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource. (Lost)
     *
     * @return \Illuminate\Http\Response
     */
    public function create_lost()
    {
        return view('pages.laf.lost.create', [
            'categories' => Category::where('visible', 1)->get(),
            'colors' => Color::where('visible', 1)->get(),
            'locations' => Location::where('visible', 1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage. (Item)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_item(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|unique:App\Models\Investigation,reference',
            'item' => 'required|min:3',
            'category' => 'required|exists:categories,id',
            'color' => 'array|exists:colors,id|nullable',
            'description' => 'required',
            'condition' => 'nullable',
            'date' => 'required|date',
            'location' => 'nullable',
            'additional_info' => 'nullable',
            'storage' => 'required|exists:locations,id',
        ], [
            'reference.unique' => 'Den tilfeldig genererte referansen er ikke unik, trykk lagre på nytt',
            'item.required' => 'En gjenstand type kreves',
            'item.min' => 'Gjenstand type må være lengre enn 3 tegn',
            'category.required' => 'En kategori kreves',
            'category,exists' => 'Kategorien valgt er ugyldig',
            'color.array' => 'Valgte farger er ikke en liste (array)',
            'color.exists' => 'En av de valgte fargene er ugyldig',
            'description.required' => 'En beskrivelse kreves',
            'date.required' => 'Dato tapt kreves',
            'date.date' => 'Datoformatet er feil',
            'storage.required' => 'Lagringslokasjon kreves',
            'storage.exists' => 'Den valgte lagringslokasjonen finnes ikke'
        ]);

        $inv = Investigation::create([
            'reference' => $validated['reference'],
            'item' => $validated['item'],
            'category_id' => $validated['category'],
            'description' => $validated['description'],
            'condition' => $validated['condition'],
            'status' => 'found',
            'initial_status' => 'found',
            'lost_date' => date('Y-m-d', strtotime($validated['date'])),
            'lost_location' => $validated['location'],
            'location_id' => $validated['storage'],
            'additional_info' => $validated['additional_info'],
            'user_id' => auth()->user()->id
        ]);

        $inv->colors()->sync($validated['color']);

        Conversation::create([
            'investigation_id' => $inv->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => 'Gjenstanden ble opprettet'
        ]);

        return redirect('/case/' . $validated["reference"]);
    }

    /**
     * Store a newly created resource in storage. (Lost)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_lost(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|unique:App\Models\Investigation,reference',
            'item' => 'required|min:3',
            'category' => 'required|exists:categories,id',
            'color' => 'array|exists:colors,id|nullable',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'nullable',
            'additional_info' => 'nullable',
            'owner_name' => 'required',
            'owner_email' => 'required_without:owner_phone|email|nullable',
            'owner_phone' => 'required_without:owner_email|phone:auto|nullable',
        ], [
            'reference.unique' => 'Den tilfeldig genererte referansen er ikke unik, trykk lagre på nytt',
            'item.required' => 'En gjenstand type kreves',
            'item.min' => 'Gjenstand type må være lengre enn 3 tegn',
            'category.required' => 'En kategori kreves',
            'category,exists' => 'Kategorien valgt er ugyldig',
            'color.array' => 'Valgte farger er ikke en liste (array)',
            'color.exists' => 'En av de valgte fargene er ugyldig',
            'description.required' => 'En beskrivelse kreves',
            'date.required' => 'Dato tapt kreves',
            'date.date' => 'Datoformatet er feil',
            'owner_name.required' => 'Et navn kreves',
            'owner_email.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_email.email' => 'E-Posten er ikke gyldig',
            'owner_phone.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_phone.phone' => 'Telefonnummeret er ugyldig'
        ]);

        $inv = Investigation::create([
            'reference' => $validated['reference'],
            'item' => $validated['item'],
            'category_id' => $validated['category'],
            'description' => $validated['description'],
            'status' => 'lost',
            'initial_status' => 'lost',
            'lost_date' => date('Y-m-d', strtotime($validated['date'])),
            'lost_location' => $validated['location'],
            'additional_info' => $validated['additional_info'],
            'owner_name' => $validated["owner_name"],
            'owner_email' => $validated["owner_email"],
            'owner_phone' => $validated["owner_phone"],
            'user_id' => auth()->user()->id
        ]);

        $inv->colors()->sync($validated['color']);

        $url = route('public_case_view', ['reference' => $validated['reference'], 'lost_date' => $inv->lost_date]);

        if (env('EMAIL_ENABLED') && isset($validated["owner_email"])) {
            Mail::to($validated["owner_email"])->queue(new NewLostNotification($inv));

            $txt = "Etterlysningen ble opprettet og et varsel ble sendt via E-Post til gjest";
        }

        if (env('SMS_ENABLED') && isset($validated["owner_phone"])) {
            $text = "Hei ". $validated['owner_name'] .", Det har blitt opprettet en etterlysning med refereanse ". $validated['reference'] .". Du kan vise den her ". $url ." . På linken kan du også laste opp bilder for å hjelpe oss å finne din savnede eiendel. Vi ønsker deg en frydefull dag! Mvh Gjesteservice TusenFryd";
            $recipent = $validated["owner_phone"];

            ProcessSMS::dispatchAfterResponse($recipent, $text);

            $txt = "Etterlysningen ble opprettet og et varsel ble sendt på SMS til gjest";
        }

        if (isset($validated["owner_phone"]) && isset($validated["owner_email"]) && env('SMS_ENABLED') && env('EMAIL_ENABLED')) {
            $txt = "Etterlysningen ble opprettet og et varsel ble sendt på SMS og E-Post til gjest";
        }

        Conversation::create([
            'investigation_id' => $inv->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => $txt
        ]);


        return redirect('/case/' . $validated["reference"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $case = Investigation::where('reference', $id)->firstOrFail();

        return view('pages.laf.casedetails', [
            'case' => $case,
            'statemachine' => StateMachine::get($case, 'investigation'),
            'latestaudituser' => User::find($case->audits()->latest()->first()->getMetadata()["user_id"]),
            'media' => $case->getMedia('caseimages')->sortByDesc('id'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function public_view($id, $lostdate)
    {
        $case = Investigation::where('reference', $id)->where('lost_date', $lostdate)->firstOrFail();

        return view('pages.public.caseview', [
            'case' => $case,
            'messages' => Conversation::where('investigation_id', $case->id)->orderBy('created_at', 'desc')->paginate(5),
            'statemachine' => StateMachine::get($case, 'investigation'),
            'media' => $case->getMedia('caseimages')->sortByDesc('id'),
        ]);
    }

    public function public_edit(Request $request, $id, $lostdate)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $validated = $request->validate([
            'owner_name' => 'required',
            'owner_email' => 'required_without:owner_phone|email|nullable',
            'owner_phone' => 'required_without:owner_email|phone:auto|nullable',
        ], [
            'owner_name.required' => 'Et navn kreves',
            'owner_email.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_email.email' => 'E-Posten er ikke gyldig',
            'owner_phone.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_phone.phone' => 'Telefonnummeret er ugyldig'
        ]);

        $case = Investigation::where('reference', $id)->where('lost_date', $lostdate)->firstOrFail();

        $case->update($validated);

        $case->save();

        $conv = Conversation::create([
            'investigation_id' => $case->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => "Gjesten oppdaterte sin kontaktinformasjon"
        ]);

        return redirect(route('public_case_view', ['reference' => $case->reference, 'lost_date' => $case->lost_date]))->with(array('message' => 'Endringene ble lagret', 'status' => 'success'));
    }

    public function public_delete(Request $request, $id, $lostdate) {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $case = Investigation::where('reference', $id)->where('lost_date', $lostdate)->firstOrFail();

        $case->colors()->detach();

        Conversation::where('investigation_id', $case->id)->delete();

        $case = Investigation::where('reference', $id)->delete();

        return redirect()->away('https://tusenfryd.no');
    }

    /**
     * Display the specified resources edit history
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_edithistory($id)
    {
        $case = Investigation::where('reference', $id)->firstOrFail();

        return view('pages.laf.caseedithistory', [
            'case' => $case,
        ]);
    }

    /**
     * Display the specified resources images
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_images(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $case = Investigation::where('reference', $id)->firstOrFail();

        return view('panels.laf.imagecarousel', [
            'case' => $case,
            'media' => $case->getMedia('caseimages')->sortByDesc('id'),
        ]);
    }

    /**
     * Display the specified resources images
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_images(Request $request,$id)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
            return response()->json([
                'status' => false
            ]);
        }

        $validated = $request->validate([
            'file.*' => 'required|image',
            'from_guest' => 'nullable'
        ], [
            'file.required' => 'Mangler filer',
            'file.image' => 'Filen(e) er ikke bilder',
        ]);

        $case = Investigation::where('reference', $id)->firstOrFail();

        foreach ($validated["file"] as $file) {
            $case->addMedia($file)->toMediaCollection('caseimages');
        }

        if ($validated["from_guest"] == 'false') {
            $from_guest = false;
        } else {
            $from_guest = true;
        }

        Conversation::create([
            'investigation_id' => $case->id,
            'messagetype' => 'notification',
            'processed' => $from_guest,
            'from_guest' => $from_guest,
            'message' => "Det ble lastet opp nye bilder"
        ]);

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy_image(Request $request, $id, $image)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
            return response()->json([
                'status' => false
            ]);
        }

        $media = Media::where('id', $image)->firstOrFail();

        return response()->json([
            'status' => $media->delete()
        ]);
    }

    /**
     * Show the form for linking two cases
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function link($ref1, $ref2)
    {
        $case1 = Investigation::where('reference', $ref1)->firstOrFail();

        $case2 = Investigation::where('reference', $ref2)->firstOrFail();

        if ($case1->initial_status == $case2->initial_status) {
            return view('pages.laf.linkcase', [
                'compare_only' => true,
                'case1' => $case1,
                'case2' => $case2,
                'categories' => Category::where('visible', 1)->get(),
                'colors' => Color::where('visible', 1)->get(),
                'locations' => Location::where('visible', 1)->get(),
            ]);
        }

        //Determine which of them is "lost" and which is "found"
        if ($case1->initial_status == "found" && $case2->initial_status == "lost") {
            return view('pages.laf.linkcase', [
                'compare_only' => false,
                'case1' => $case1,
                'case2' => $case2,
                'categories' => Category::where('visible', 1)->get(),
                'colors' => Color::where('visible', 1)->get(),
                'locations' => Location::where('visible', 1)->get(),
                ]);
        } elseif($case2->initial_status == "found" && $case1->initial_status == "lost") {
            return view('pages.laf.linkcase', [
                'compare_only' => false,
                'case1' => $case2,
                'case2' => $case1,
                'categories' => Category::where('visible', 1)->get(),
                'colors' => Color::where('visible', 1)->get(),
                'locations' => Location::where('visible', 1)->get(),
            ]);
        } else {
            return view('pages.laf.linkcase', [
                'compare_only' => true,
                'case1' => $case1,
                'case2' => $case2,
                'categories' => Category::where('visible', 1)->get(),
                'colors' => Color::where('visible', 1)->get(),
                'locations' => Location::where('visible', 1)->get(),
            ]);
        }
    }

    /**
     * Update the specified resource to be merged with other case
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function link_update(Request $request, $ref1, $ref2)
    {
        $validated = $request->validate([
            'reference' => 'required|exists:investigations,reference',
            'item' => 'required|min:3',
            'description' => 'required',
            'color' => 'array|exists:colors,id|nullable',
            'condition' => 'nullable',
            'lost_location' => 'nullable',
            'date' => 'required|date',
            'category' => 'required|exists:categories,id',
            'location' => 'required|exists:locations,id', //Location ID for the storage location
            'owner_name' => 'required',
            'owner_email' => 'required_without:owner_phone|email|nullable',
            'owner_phone' => 'required_without:owner_email|phone:auto|nullable',
            'additional_info' => 'nullable',
        ], [
            'reference.required' => 'Referanse mangler',
            'reference.exists' => 'Referanse finnes ikke fra før',
            'item.required' => 'En gjenstand tittel kreves',
            'item.min' => 'Gjenstand tittel må være lengre enn 3 tegn',
            'description.required' => 'En beskrivelse kreves',
            'color.array' => 'Valgte farger er ikke en liste (array)',
            'color.exists' => 'En av de valgte fargene er ugyldig',
            'date.required' => 'Dato tapt kreves',
            'date.date' => 'Datoformatet er feil',
            'category.required' => 'En kategori kreves',
            'category,exists' => 'Kategorien valgt er ugyldig',
            'location.required' => 'En lagringsplass kreves',
            'location,exists' => 'Lagringsplassen valgt er ugyldig',
            'owner_name.required' => 'Et navn kreves',
            'owner_email.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_email.email' => 'E-Posten er ikke gyldig',
            'owner_phone.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_phone.phone' => 'Telefonnummeret er ugyldig'
        ]);

        $case1 = Investigation::where('reference', $ref1)->firstOrFail();
        $case1->reference = $validated["reference"];
        $case1->item = $validated["item"];
        $case1->description = $validated["description"];
        $case1->condition = $validated["condition"];
        $case1->lost_location = $validated["lost_location"];
        $case1->location_id = $validated["location"];
        $case1->category_id = $validated["category"];
        $case1->status = 'wait_for_delivery';
        $case1->lost_date = date('Y-m-d', strtotime($validated['date']));
        $case1->owner_name = $validated["owner_name"];
        $case1->owner_email = $validated["owner_email"];
        $case1->owner_phone = $validated["owner_phone"];
        $case1->additional_info = $validated["additional_info"];
        $case1->colors()->sync($validated['color']);
        //This one transitions to the next state
        //$stateMachine = StateMachine::get($case1, 'investigation');
        //$stateMachine->apply('wait_for_delivery');

        $case1->save();

        $case2 = Investigation::where('reference', $ref2)->firstOrFail();
        //Dont bother to use the state machine, just force it into the canceled state without logging
        $case2->status = 'canceled';
        $case2->save();

        Conversation::create([
            'investigation_id' => $case1->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => "Saken ble slått sammen med $ref2 og gitt ny status"
        ]);

        Conversation::create([
            'investigation_id' => $case2->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => "Saken ble avsluttet etter å ha blitt slått sammen med $ref1"
        ]);


        return redirect('/case/' . $validated["reference"]);
    }

    public function check_alive($id)
    {
        $case = Investigation::where('reference', $id)->firstOrFail();

        if(isset($case->reference)) {
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false
            ]);
        }
    }


    /**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function edit($id)
    {
        $case = Investigation::where('reference', $id)->firstOrFail();

        return view('pages.laf.editcase', [
            'case' => $case,
            'categories' => Category::where('visible', 1)->get(),
            'colors' => Color::where('visible', 1)->get(),
            'locations' => Location::where('visible', 1)->get()
        ]);
    }

    /**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function update(Request $request, $id)
    {
        //Validate
        $validated = $request->validate([
            'item' => 'required',
            'category_id' => 'required|exists:categories,id',
            'color' => 'array|exists:colors,id|nullable',
            'condition' => 'nullable',
            'description' => 'required',
            'lost_date' => 'required|date',
            'lost_location' => 'nullable',
            'location_id' => 'required_if:require_locationpos,true|exists:locations,id|nullable',
            'owner_name' => 'required_if:require_names,true',
            'owner_email' => 'required_without:owner_phone|email|nullable',
            'owner_phone' => 'required_without:owner_email|phone:auto|nullable',
            'additional_info' => 'nullable',
        ], [
            'item.required' => 'En gjenstand tittel kreves',
            'description.required' => 'En beskrivelse kreves',
            'color.array' => 'Valgte farger er ikke en liste (array)',
            'color.exists' => 'En av de valgte fargene er ugyldig',
            'date.required' => 'Dato tapt kreves',
            'date.date' => 'Datoformatet er feil',
            'category_id.required' => 'En kategori kreves',
            'category_id.exists' => 'Kategorien valgt er ugyldig',
            'location_id.required_if' => 'En lagringsplass kreves',
            'location_id.exists' => 'Lagringsplassen valgt er ugyldig',
            'owner_name.required_if' => 'Et navn kreves',
            'owner_email.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_email.email' => 'E-Posten er ikke gyldig',
            'owner_phone.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_phone.phone' => 'Telefonnummeret er ugyldig'
        ]);

        $update = $validated;

        unset($update["color"]);

        //Pull the case
        $case = Investigation::where('reference', $id)->firstorFail();

        $case->update($update);

        $case->save();

        return redirect('/case/' . $case->reference)->with(array('message' => "Endringene ble lagret", 'status' => 'success'));
    }

    /**
     * Update the specified resources status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_status($ref, $wantedstate)
    {
        //Pull the case
        $case = Investigation::where('reference', $ref)->firstorFail();

        //Init the statemachine
        $sm = StateMachine::get($case, 'investigation');

        //Check if the wanted action is a regret
        if ($wantedstate == "regret") {
            $stateHistory = $case->stateHistory()->get();
            //Check if the state history is existing
            if (count($stateHistory) > 0) {

                //Has history
                $latestelement = current($stateHistory);

                $el = end($latestelement);

                $latestState = $el->from;

                //dd($latestState);

                $wantedstate = "regret_to_$latestState";
            } else {
                return redirect('/case/' . $case->reference)->with(array('message' => "Det er ingenting å angre på denne saken", 'status' => 'danger'));
            }
        }

        //Check if the transaction can be applied and that it is not the same state (just for safety)
        if ($sm->can($wantedstate) && ($sm->getState() !== $wantedstate)) {
            try {
                $sm->apply($wantedstate);

                $currentState = $sm->metadata('state', 'title');

                $case->save();

                Conversation::create([
                    'investigation_id' => $case->id,
                    'messagetype' => 'notification',
                    'from_guest' => false,
                    'message' => "Statusen ble oppdatert til '$currentState'"
                ]);

                return redirect('/case/' . $case->reference)->with(array('message' => "Statusen ble oppdatert", 'status' => 'success'));
            } catch (SMException $e) {
                return redirect('/case/' . $case->reference)->with(array('message' => "Kan ikke overføre saken til denne statusen: $e", 'status' => 'danger'));
            }
        } else {
            return redirect('/case/' . $case->reference)->with(array('message' => 'Kan ikke overføre saken til denne statusen', 'status' => 'danger'));
        }
    }

    public function update_status_force(Request $request, $ref) {
        $validated = $request->validate([
            'status' => 'required|in:evicted,police,wait_for_police,canceled,wait_for_delivery,wait_for_send,sent,wait_for_pickup,picked_up',
        ], [
            'status.required' => 'Status kreves',
            'status.in' => 'Statusen er ikke gydlig',
        ]);

        //Pull the case
        $case = Investigation::where('reference', $ref)->firstorFail();

        $case->status = $request->input('status');

        $case->save();

        Conversation::create([
            'investigation_id' => $case->id,
            'messagetype' => 'notification',
            'from_guest' => false,
            'message' => "Statusen ble oppdatert med tvang"
        ]);

        return redirect('/case/' . $case->reference)->with(array('message' => "Statusen ble oppdatert med tvang", 'status' => 'success'))->send();
    }

    public function update_status_withowner(Request $request, $ref) {
        $validated = $request->validate([
            'status' => 'required|in:evicted,police,wait_for_police,canceled,wait_for_delivery,wait_for_send,sent,wait_for_pickup,picked_up',
            'owner_name' => 'required',
            'owner_email' => 'required_without:owner_phone|email|nullable',
            'owner_phone' => 'required_without:owner_email|phone:auto|nullable',
        ], [
            'status.required' => 'Status kreves',
            'status.in' => 'Statusen er ikke gydlig',
            'owner_name.required' => 'Et navn kreves',
            'owner_email.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_email.email' => 'E-Posten er ikke gyldig',
            'owner_phone.required_without' => 'Enten en E-Post eller et telefonnummer kreves',
            'owner_phone.phone' => 'Telefonnummeret er ugyldig'
        ]);

        //Pull the case
        $case = Investigation::where('reference', $ref)->firstorFail();

        //Init the statemachine
        $sm = StateMachine::get($case, 'investigation');

        //Check if the transaction can be applied and that it is not the same state (just for safety)
        if ($sm->can($request->input('status')) && ($sm->getState() !== $request->input('status'))) {
            try {
                $sm->apply($request->input('status'));

                $currentState = $sm->metadata('state', 'title');

                $case->owner_name = $request->input('owner_name');
                $case->owner_email = $request->input('owner_email');
                $case->owner_phone = $request->input('owner_phone');

                $case->save();

                Conversation::create([
                    'investigation_id' => $case->id,
                    'messagetype' => 'notification',
                    'from_guest' => false,
                    'message' => "Statusen ble oppdatert til '$currentState'"
                ]);

                return redirect('/case/' . $case->reference)->with(array('message' => "Statusen ble oppdatert og kontaktinformasjon lagret", 'status' => 'success'));
            } catch (SMException $e) {
                return redirect('/case/' . $case->reference)->with(array('message' => "Kan ikke overføre saken til denne statusen: $e", 'status' => 'danger'));
            }
        } else {
            return redirect('/case/' . $case->reference)->with(array('message' => 'Kan ikke overføre saken til denne statusen', 'status' => 'danger'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $case = Investigation::where('reference', $id)->firstOrFail();

        $case->colors()->detach();

        Conversation::where('investigation_id', $case->id)->delete();

        $case = Investigation::where('reference', $id)->delete();

        return redirect('/home')->with(array('message' => 'Saken ble slettet', 'status' => 'info'));
    }
}
