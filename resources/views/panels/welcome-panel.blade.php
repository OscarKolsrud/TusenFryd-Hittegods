@php

    $levelAmount = 'level';

    if (Auth::User()->level() >= 2) {
        $levelAmount = 'levels';

    }

@endphp

<div class="card">
    <div class="card-header">

        Hei {{ Auth::user()->name }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center">
            <a role="button" href="{{ route('create_item') }}" class="btn btn-outline-primary mr-3"><i class="fa fa-plus" aria-hidden="true"></i> Opprett gjenstand</a>
            <a role="button" href="{{ route('create_lost') }}" class="btn btn-outline-primary"><i class="fa fa-plus" aria-hidden="true"></i> Opprett etterlysning</a>
        </div>

        <div class="mt-5 text-center">
            <h4 class="mb-3">Søk etter sak</h4>
            <div id="searchbox"></div>
            <div id="refinement-list"></div>
            <div id="hits"></div>
            <center>
                <div id="pagination"></div>
            </center>
        </div>
        <div class="mt-2">
            <p>
                found=Registrert tapt (Etterlysning), lost=Registrert mistet (Gjenstand)
            </p>
        </div>
        </div>

    </div>
</div>

<div class="card mt-3">
    <div class="card-header">Oppdateringer fra gjester</div>
    <div class="card-body">
        <div class="text-center">
            <h5>Meldinger/oppdateringer fra gjester</h5>
            @forelse ($unread as $conversation)
                @php
                    $case = \App\Models\Investigation::find($conversation->investigation_id);
                @endphp

                <div>
                    @if($conversation->from_guest && $conversation->messagetype == 'message')
                        <span class="font-weight-bold mb-2"><i class="fa fa-comments" aria-hidden="true"></i> Gjest</span> @if(!$conversation->processed)<div class="btn-group btn-group-sm" role="group" aria-label="Actions"><a class="btn btn-primary btn-sm" href="{{ route('show_case', ['reference' => $case->reference]) }}" role="button">Åpne sak</a><button disabled type="button" class="btn btn-secondary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', '{{ $case->reference }}');">Åpne sak for å behandle</button></div>@endif<br>
                        <span>{{ $conversation->message }}</span><br>
                    @elseif(!$conversation->from_guest && $conversation->messagetype == 'message')
                        <span class="font-weight-bold mb-2"><i class="fa fa-comment" aria-hidden="true"></i> {{ $conversation->user->first_name }} - Gjesteservice Tusenfryd</span> @if(!$conversation->processed)<div class="btn-group btn-group-sm" role="group" aria-label="Actions"><a class="btn btn-primary btn-sm" href="{{ route('show_case', ['reference' => $case->reference]) }}" role="button">Åpne sak</a><button disabled type="button" class="btn btn-secondary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', '{{ $case->reference }}');">Åpne sak for å behandle</button></div>@endif<br>
                        <span>{{ $conversation->message }}</span><br>

                    @elseif(!$conversation->from_guest && $conversation->messagetype == 'phone')
                        <span class="font-weight-bold mb-2"><i class="fa fa-phone" aria-hidden="true"></i>Gjesten ble ringt av {{ $conversation->user->first_name }}</span> @if(!$conversation->processed)<div class="btn-group btn-group-sm" role="group" aria-label="Actions"><a class="btn btn-primary btn-sm" href="{{ route('show_case', ['reference' => $case->reference]) }}" role="button">Åpne sak</a><button disabled type="button" class="btn btn-secondary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', '{{ $case->reference }}');">Åpne sak for å behandle</button></div>@endif<br>
                        <span>{{ $conversation->message }}</span><br>
                    @else
                        <span class="font-weight-bold mb-2"><i class="fa fa-bullhorn" aria-hidden="true"></i>{{ $conversation->message }}</span> @if(!$conversation->processed)<div class="btn-group btn-group-sm" role="group" aria-label="Actions"><a class="btn btn-primary btn-sm" href="{{ route('show_case', ['reference' => $case->reference]) }}" role="button">Åpne sak</a><button disabled type="button" class="btn btn-secondary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', '{{ $case->reference }}');">Åpne sak for å behandle</button></div>@endif<br>
                    @endif
                    <small class="font-weight-bold">Sak: {{ $case->reference }}</small> // <small class="font-italic">Tidspunkt: {{ date('H:s d.m.Y', strtotime($conversation->created_at)) }}</small>
                    <hr>
                </div>
            @empty
                <div>
                    <span>Ingen ubehandlede meldinger 🎉</span>
                </div>
            @endforelse

            <div class="mt-3 d-flex justify-content-center">
                {{ $unread->links() }}
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">Dine 5 siste viste saker denne økten</div>
    <div class="card-body">
        @forelse (session('recentInvestigations') ?: [] as $sess)
            @if($loop->first)
                <div class="d-flex justify-content-center">
                    <table class="table table-borderless w-50">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">Referanse</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif
                        <tr>
                            <th scope="row" class="text-center">{{ $sess }}</th>
                            <td class="text-right"><a class="btn btn-primary" href="{{ route('show_case', ['reference' => $sess]) }}" role="button">Gå til sak</a>
                            </td>
                        </tr>
                        @if($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            <div class="text-center">
                Du har ikke vist noen saker enda
            </div>
        @endforelse
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">Gammelt søk</div>
    <div class="card-body">
        <form action="{{ route('get_search') }}" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Søkeord (F.eks: svart iphone)" name="query" id="searchQuery" aria-label="Søkeord" aria-describedby="searchButton">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" id="searchButton">Søk alle saker</button>
                </div>
            </div>
        </form>
</div>
