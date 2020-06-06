<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Jobs\ProcessSMS;
use App\Mail\NewMessageNotification;
use App\Models\Investigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConversationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_message(Request $request)
    {
        $validated = $request->validate([
            'investigation_id' => 'required|exists:investigations,id',
            'messagetype' => 'required|in:message,phone',
            'message' => 'required',
            'notify' => 'nullable'
        ], [
            'investigation_id.required' => 'IDen til saken mangler',
            'investigation_id.exists' => 'Saks IDen eksisterer ikke',
            'messagetype.required' => 'Meldingstype mangler',
            'messagetype.in' => 'Meldingstypen er ikke tilgjengelig',
            'message.required' => 'Det kreves en melding',
        ]);

        $case = Investigation::findOrFail($validated['investigation_id']);

        $inv = Conversation::create([
            'investigation_id' => $validated['investigation_id'],
            'messagetype' => $validated['messagetype'],
            'from_guest' => false,
            'user_id' => auth()->user()->id,
            'message' => $validated['message']
        ]);

        $url = route('public_case_view', ['reference' => $case->reference, 'lost_date' => $case->lost_date]);

        if (empty($validated["notify"])) {
            $notify = true;
        } elseif($validated["notify"] == false) {
            $notify = false;
        } else {
            $notify = true;
        }

        if (env('EMAIL_ENABLED') && isset($case->owner_email) && $notify) {
            Mail::to($case->owner_email)->queue(new NewMessageNotification($case));
        }

        if (env('SMS_ENABLED') && isset($case->owner_phone) && $notify) {
            if ($validated['messagetype'] == 'message') {
                $text = "Hei ". $case->owner_name .", Vi har sendt deg en ny melding i relasjon til sak ". $case->reference .". Vi setter pris på om du sjekker den. Du kan vise den her ". $url ." Vi ønsker deg en frydefull dag! Mvh Gjesteservice TusenFryd";
            } elseif ($validated['messagetype'] == 'phone') {
                $text = "Hei ". $case->owner_name .", Vi har nettopp forsøkt å ringe deg i relasjon til sak ". $case->reference .". Du kan gjerne ringe oss opp dersom dette passer deg, ellers kan vise saken her ". $url ." Vi ønsker deg en frydefull dag! Mvh Gjesteservice TusenFryd";
            }

            $recipent = $case->owner_phone;

            ProcessSMS::dispatchAfterResponse($recipent, $text);
        }

        return redirect('/case/' . $case->reference)->with(array('message' => 'Hendelse/Melding ble lagret', 'status' => 'success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_message_public(Request $request, $reference, $lost_date)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $validated = $request->validate([
            'message' => 'required',
        ], [
            'message.required' => 'Det kreves en melding',
        ]);

        $case = Investigation::where('reference', $reference)->where('lost_date', $lost_date)->firstOrFail();

        $inv = Conversation::create([
            'investigation_id' => $case->id,
            'messagetype' => 'message',
            'processed' => false,
            'from_guest' => true,
            'message' => $validated['message']
        ]);

        return redirect(route('public_case_view', ['reference' => $case->reference, 'lost_date' => $case->lost_date]))->with(array('message' => 'Meldingen ble lagret og vil bli behandlet så snart som mulig', 'status' => 'success'));
    }

    public function mark_read(Request $request) {
        $validated = $request->validate([
            'message' => 'required_without_all:all,case|exists:conversations,id|nullable',
            'all' => 'required_with:case|boolean|nullable',
            'case' => 'required_with:all|exists:investigations,reference|nullable'
        ], [
            'message.required' => 'IDen mangler i forespørselen',
            'message.exists' => 'IDen eksisterer ikke',
            'all.boolean' => 'Feil datatype sendt',
        ]);


        if (isset($validated["all"]) && $validated["all"] == true) {
            //Mark all related to a case as read
            $case = Investigation::where('reference', $validated["case"])->firstOrFail();

            Conversation::where('investigation_id', $case->id)->update(['processed' => 1]);

            return response()->json([
                'status' => true
            ]);
        } else {
            Conversation::where('id', $validated["message"])->update(['processed' => 1]);

            return response()->json([
                'status' => true
            ]);

            return response()->json([
                'status' => $message->save()
            ]);
        }
    }
}
