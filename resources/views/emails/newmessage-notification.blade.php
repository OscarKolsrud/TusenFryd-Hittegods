@component('mail::message')
# Oppdatering på din etterlysning

Hei {{ $investigation->owner_name }},

Vi har en oppdatering til deg angående din etterlysning etter "{{ $investigation->item }}" med referanse <b>{{ $investigation->reference }}</b>, som ble registrerte hos oss {{ date('d.m.Y', strtotime($investigation->created_at)) }}.

Vi setter pris på om du kan <b>svare så fort som mulig</b>, det vil ikke bli sendt flere varsel om denne oppdateringen.

Klikk knappen under for å vise oppdateringen og etterlysningen:

@component('mail::button', ['url' => route('public_case_view', ['reference' => $investigation->reference, 'lost_date' => $investigation->lost_date])])
Vis Oppdatering & Etterlysning
@endcomponent


Funker ikke knappen kan du bruke: {{ route('public_case_view', ['reference' => $investigation->reference, 'lost_date' => $investigation->lost_date]) }}
<hr>

Med frydefull hilsen / With best regards<br>
Gjesteservice TusenFryd
@endcomponent
