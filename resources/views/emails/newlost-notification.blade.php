@component('mail::message')

# Bekreftelse på ny etterlysning

Hei {{ $investigation->owner_name }},

Vi har nå registrert etterlysningen din etter "{{ $investigation->item }}" og tildelt den referansenummer <b>{{ $investigation->reference }}</b>.
Vi vil kontakte deg dersom vi får inn en gjenstand som kan ligne det du har etterlyst.

Trykker du på knappen under vil du kunne <b>laste opp bilder</b>, sende oss meldinger og se status på din etterlysning.
Sender vi deg en melding vil du motta en e-post og SMS (Dersom du registrerte ditt tlf. nummer). På knappen finner du også informasjon
om hvordan dataen din vil bli behandlet og har mulighet til å slette etterlysningen.

@component('mail::button', ['url' => route('public_case_view', ['reference' => $investigation->reference, 'lost_date' => $investigation->lost_date])])
Vis Etterlysning & Administrer
@endcomponent


Funker ikke knappen kan du bruke: {{ route('public_case_view', ['reference' => $investigation->reference, 'lost_date' => $investigation->lost_date]) }}
<hr>

Med frydefull hilsen / With best regards<br>
Gjesteservice TusenFryd
@endcomponent
