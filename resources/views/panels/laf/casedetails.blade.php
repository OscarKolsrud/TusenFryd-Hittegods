<div class="container">
    <div class="row">
        <div class="col-9 col-lg-9">
            <div class="card mb-3">
                <div class="card-header">

                    Sak {{ $case->reference }}

                    @role('admin', true)
                    <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
                    @endrole

                </div>
                <div class="card-body">
                    <h2><span class="font-weight-bold">Gjenstand:</span> {{ Str::ucfirst($case->item) }}</h2>
                    <hr>
                    <h3 class="font-weight-bold">Beskrivelse</h3>
                    <div class="mb-3">
                        {{ $case->description }}
                    </div>
                    <hr>
                    <h3 class="font-weight-bold">Annen informasjon/notater</h3>
                    <div class="mb-3">
                        {{ $case->additional_info ?? 'Ingen informasjon/notater tilgjengelig'}}
                    </div>
                    <hr>
                    <h3 class="font-weight-bold">Bilder</h3>
                    <div class="mb-3">
                        <div class="mb-3">
                            @include('panels.laf.imagecarousel')
                        </div>
                        @if(!$statemachine->metadata('state', 'resolution'))
                            <form action="{{ URL::temporarySignedRoute('store_images', now()->addMinutes(60), ['reference' => $case->reference]) }}" method="post" class="dropzone" id="image-dropzone" enctype="multipart/form-data">
                                @csrf
                                <div class="dz-message needsclick">
                                    <span class="font-weight-bold">Dra filer hit eller klikk for å laste opp</span><br>
                                    <span class="font-weight-bold">Maksimalt 5 filer pr. opplastning</span>
                                </div>

                                <input type="text" name="from_guest" value="false" required hidden>
                            </form>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-primary mb-2" id="submitImages">Last opp nye bilder</button>
                                <br>
                                <small class="font-italic">Dette vil ikke varsle Gjesten</small>
                            </div>
                        @endif
                    </div>
                    <hr>
                    <div>
                        <span class="font-weight-bold">Farge(r): </span>
                        @forelse ($case->colors as $color)
                            <span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span>
                        @empty
                            <span>Ingen registrert</span>
                        @endforelse
                        <br>
                        @if($case->initial_state == 'found')
                            <span class="font-weight-bold">Tilstandsbeskrivelse: </span>{{ $case->condition ?? 'Ikke registrert' }}
                            <br>
                        @endif
                        <span data-toggle="tooltip" data-html="true" data-placement="bottom" title="<b>Beskrivelse: </b>{{ $case->category->description }}"><span class="font-weight-bold">Kategori: </span>{{ $case->category->category_name ?? 'Ikke tilgjengelig' }}
                        <br>
                        <small class="font-italic">Hold musen over for beskrivelse av kategori</small></span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Hendelser & Meldinger</div>
                <div class="card-body">
                    <div class="mb-5">
                        <form action="{{ Request::url() }}/addconversation" method="post">
                            @csrf
                            <input type="text" name="investigation_id" value="{{ $case->id }}" hidden>
                            <input type="text" name="messagetype" value="message" hidden>
                            <div class="form-group">
                                <label for="message" class="font-weight-bold h4">Skriv ny melding <button type="button" class="btn btn-primary btn-sm" id="messageProcess-{{ $case->reference }}" onclick="markProcessed('{{ $case->reference }}', true);">Merk alle som behandlet</button></label>
                                <textarea class="form-control" id="message" name="message" rows="3" @if($statemachine->metadata('state', 'resolution')) disabled @endif>@if($statemachine->metadata('state', 'resolution')) Nye meldinger er ikke mulig fordi saken er ansett som ferdig @endif</textarea>
                            </div>
                            <div class="form-group">
                                <div class="btn-group float-right" role="group" aria-label="Flere valg">
                                    <button type="button" class="btn btn-secondary mb-2" data-toggle="modal" data-target="#moreActionsModal" @if($statemachine->metadata('state', 'resolution')) disabled @endif>Flere handlinger</button>
                                    <button type="submit" class="btn btn-primary mb-2" @if($statemachine->metadata('state', 'resolution')) disabled @endif>Send melding</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <label for="history" class="font-weight-bold h4">Tidligere hendelser & Meldinger</label>
                    <div id="history">
                        @forelse ($case->conversations as $conversation)
                            <div>
                                @if($conversation->from_guest && $conversation->messagetype == 'message')
                                    <span class="font-weight-bold mb-2"><i class="fa fa-comments" aria-hidden="true"></i> Gjest</span> @if(!$conversation->processed)<button type="button" class="btn btn-primary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', false);">Merk behandlet</button>@endif<br>
                                    <span>{{ $conversation->message }}</span><br>
                                @elseif(!$conversation->from_guest && $conversation->messagetype == 'message')
                                    <span class="font-weight-bold mb-2"><i class="fa fa-comment" aria-hidden="true"></i> {{ $conversation->user->first_name }} - Gjesteservice Tusenfryd</span> @if(!$conversation->processed)<button type="button" class="btn btn-primary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', false);">Merk behandlet</button>@endif<br>
                                    <span>{{ $conversation->message }}</span><br>

                                @elseif(!$conversation->from_guest && $conversation->messagetype == 'phone')
                                    <span class="font-weight-bold mb-2"><i class="fa fa-phone" aria-hidden="true"></i>Gjesten ble ringt av {{ $conversation->user->first_name }}</span> @if(!$conversation->processed)<button type="button" class="btn btn-primary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', false);">Merk behandlet</button>@endif<br>
                                    <span>{{ $conversation->message }}</span><br>
                                @else
                                    <span class="font-weight-bold mb-2"><i class="fa fa-bullhorn" aria-hidden="true"></i>{{ $conversation->message }}</span> @if(!$conversation->processed)<button type="button" class="btn btn-primary btn-sm" id="messageProcess-{{ $conversation->id }}" onclick="markProcessed('{{ $conversation->id }}', false);">Merk behandlet</button>@endif<br>
                                @endif
                                <small class="font-italic">Tidspunkt: {{ date('H:s d.m.Y', strtotime($conversation->created_at)) }} // Sist Oppdatert: {{ date('H:s d.m.Y', strtotime($conversation->updated_at)) }}</small>
                                <hr>
                            </div>
                        @empty
                            <div>
                                <span>Ingen tilgjengelig</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-3 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h3>Sak: {{ $case->reference }}</h3>
                    @if($statemachine->metadata('state', 'resolution'))
                        <span class="text-danger font-weight-bold"><i class="fa fa-lock" aria-hidden="true"></i> Låst for redigering</span>
                    @endif
                    <div class="mb-3">
                        <span class="font-weight-bold">Status: </span><span class="badge badge-{{ $statemachine->metadata('state','class_color') }}">{{ $statemachine->metadata('state','title') }}</span><br>
                    </div>
                    @if($case->owner_name)
                        <div class="mb-3">
                            <span class="font-weight-bold">Gjest: </span>{{ Str::ucfirst($case->owner_name) ?? 'Ikke tilgjengelig' }}<br>
                            <span class="font-weight-bold">Telefon: </span>{{ $case->owner_phone ?? 'Ikke tilgjengelig' }}<br>
                            <span class="font-weight-bold">E-Post: </span>@if($case->owner_email)<a target="_blank" href="mailto:{{ $case->owner_email }}?subject=Vedrørende din hittegods sak {{ $case->reference }}">{{ $case->owner_email }}</a>@else<span>Ikke tilgjengelig</span>@endif<br>
                        </div>
                    @endif
                    <div class="mb-3">
                        <span class="font-weight-bold">Mistet: </span>{{ date('d.m.Y', strtotime($case->lost_date)) }}<br>
                        <span class="font-weight-bold">Mistet posisjon: </span>{{ Str::ucfirst($case->lost_location) ?? 'Ukjent' }}<br>
                        @if($case->location_id)
                            <div data-toggle="tooltip" data-html="true" data-placement="bottom" title="<b>Beskrivelse: </b>{{ $case->location->description }}">
                                <span class="font-weight-bold">Lager posisjon: </span>{{ Str::ucfirst($case->location->location_name) }}<br>
                                <small class="font-italic">Hold musen over for detaljer</small>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <span class="font-weight-bold">Opprettet: </span>{{ date('d.m.Y H:s', strtotime($case->created_at)) }}<br>
                        <span class="font-weight-bold">Av: </span>{{ $case->user->first_name ?? 'Ukjent' }}<br>
                        <span class="font-weight-bold">Sist redigert: </span>{{ date('d.m.Y H:s', strtotime($case->audits()->latest()->first()->getMetadata()["audit_updated_at"])) }}<br>
                        <span class="font-weight-bold">Av: </span>{{ $latestaudituser->first_name ?? 'Ukjent' }}<br>
                    </div>


                    @if(!$statemachine->metadata('state', 'resolution'))
                        <a role="button" class="btn btn-primary btn-block" href="{{ Request::url() }}/edit">Rediger sak</a>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteconfirmModal">Slett sak (Permanent)</button>
                    @endif
                    <small class="font-weight-bold d-flex justify-content-center mt-1"><a href="{{ Request::url() }}/edithistory">Historikk</a></small>
                </div>
            </div>
            <div class="card bg-light border-light">
                <div class="card-body">
                    @if(!$statemachine->metadata('state', 'resolution'))
                        <div class="text-center">
                            <small class="font-weight-bold">Sammenlign & Slå Sammen</small>
                            <div class="input-group">
                                <input type="text" class="form-control" id="link-reference" name="link-reference" maxlength="7" placeholder="Referanse">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary mb-2 float-right" onclick="redirectToLink();">Utfør</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-center">
                        <small class="font-weight-bold">Foreslåtte status endringer</small>
                        <form action="#" method="post">
                            @csrf
                            @if($statemachine->getState() == "lost")
                                <button type="button" class="btn btn-{{ $statemachine->metadata('state', 'wait_for_delivery', 'class_color') }} btn-block" data-toggle="modal" data-target="#waitDeliveryModal">Venter på utlevering</button>
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'canceled', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/cancel">Avslutt</button>
                            @elseif($statemachine->getState() == "found")
                                <button type="button" class="btn btn-{{ $statemachine->metadata('state', 'wait_for_delivery', 'class_color') }} btn-block" data-toggle="modal" data-target="#waitDeliveryModal">Venter på utlevering</button>
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'evicted', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/evicted">Kastet</button>
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'wait_for_police', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/wait_for_police">Send til politi</button>
                            @elseif($statemachine->getState() == "wait_for_police")
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'police', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/police">Send til politi</button>
                            @elseif($statemachine->getState() == "wait_for_delivery")
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'wait_for_pickup', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/wait_for_pickup">Vent på henting</button>
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'wait_for_send', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/wait_for_send">Vent på sending</button>
                            @elseif($statemachine->getState() == "wait_for_send")
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'sent', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/sent">Sendt</button>
                            @elseif($statemachine->getState() == "wait_for_pickup")
                                <button type="submit" class="btn btn-{{ $statemachine->metadata('state', 'picked_up', 'class_color') }} btn-block" formaction="/case/{{ $case->reference }}/status/picked_up">Utlevert/Hentet</button>
                            @endif

                            @if(count($case->stateHistory()->get()) > 0)
                                <button type="submit" class="btn btn-secondary btn-block" formaction="/case/{{ $case->reference }}/status/regret">Angre til forrige status</button>
                                <small>Bemerk: Har du slått sammen denne med en annen sak går det ikke ann å angre tilbake: statusen vil da bli feil</small>
                            @endif
                        </form>
                    </div>

                    <div class="text-center">
                        <small class="font-weight-bold">Endre status manuelt (Potensielt farlig)</small>
                        <form action="/case/{{ $case->reference }}/status/force" method="post">
                            @csrf
                            <div class="input-group">
                                <select class="form-control" name="status">
                                    <option value="evicted">Kastet</option>
                                    <option value="wait_for_police">Venter på politi</option>
                                    <option value="police">Sendt til politi</option>
                                    <option value="canceled">Avsluttet</option>
                                    <option value="wait_for_delivery">Venter på utlevering</option>
                                    <option value="wait_for_send">Venter på sending</option>
                                    <option value="sent">Sendt</option>
                                    <option value="wait_for_pickup">Venter på henting</option>
                                    <option value="picked_up">Hentet</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary mb-2 float-right">Lagre</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteconfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteconfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteconfirmModalLabel">Bekreft handling</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                Er du sikker på at du vil permanent slette sak <span class="font-weight-bold">{{ $case->reference }}</span>? <br>
                Den kan <span class="font-weight-bold">ikke</span> gjenopprettes.
            </div>
            <form id="delete-form" action="{{ Request::url() }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Avbryt</button>
                <button type="button" class="btn btn-danger" onclick="deletecase();">Slett</button>
            </div>
        </div>
    </div>
</div>

<!-- More actions Modal -->
<div class="modal fade" id="moreActionsModal" tabindex="-1" role="dialog" aria-labelledby="moreActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moreActionsModalLabel">Flere handlinger</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="{{ Request::url() }}/addconversation" method="post">
                    @csrf
                    <input type="text" name="investigation_id" value="{{ $case->id }}" hidden>
                    <input type="text" name="messagetype" value="phone" hidden>
                    <input type="text" name="message" value="Gjesten ble forsøkt ringt, intet svar" hidden>
                    <button type="submit" class="btn btn-danger mb-2"><i class="fa fa-phone" aria-hidden="true"></i> Gjesten ble ringt, <span class="font-weight-bold">uten</span> svar</button>
                </form>
                <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#phoneRecapModal"><i class="fa fa-phone" aria-hidden="true"></i> Gjesten ble ringt, <span class="font-weight-bold">med</span> svar</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Lukk</button>
            </div>
        </div>
    </div>
</div>

<!-- Phone recap Modal -->
<div class="modal fade" id="phoneRecapModal" tabindex="-1" role="dialog" aria-labelledby="phoneRecapModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneRecapModalLabel">Telefonsamtale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="{{ Request::url() }}/addconversation" method="post">
                    @csrf
                    <input type="text" name="investigation_id" value="{{ $case->id }}" hidden>
                    <input type="text" name="messagetype" value="phone" hidden>
                    <input type="text" name="notify" value="false" hidden>
                    <label for="message" class="font-weight-bold h4">Gi en liten oppsummering av hva som ble sagt/avtalt</label>
                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    <button type="submit" class="btn btn-primary mb-2 mt-3">Lagre</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Lukk</button>
            </div>
        </div>
    </div>
</div>

<!-- Transition to waiting_for_delivery modal -->
<div class="modal fade" id="waitDeliveryModal" tabindex="-1" role="dialog" aria-labelledby="waitDeliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="waitDeliveryModalLabel">Status "Venter på utlevering" krever bekreftelse</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <form action="/case/{{ $case->reference }}/status/withowner" method="post">
                    @csrf
                    <input type="text" name="status" value="wait_for_delivery" hidden>

                    <label for="message" class="font-weight-bold h4">Legg til/bekreft kontakinformasjon</label>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Navn*</label>
                        <input type="text" class="form-control" name="owner_name" required value="{{ old('owner_name') ?? $case->owner_name }}" placeholder="Navn">
                        @if ($errors->has('owner_name'))
                            <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Telefon</label><br>
                        <input type="tel" class="form-control" style="width: 100%;" id="owner_phone_input" name="owner_phone_input">
                        <span class="text-success hide" id="valid-msg">Gyldig</span>
                        <span class="text-danger hide" id="error-msg"></span>
                        @if ($errors->has('owner_phone'))
                            <span class="text-danger">{{ $errors->first('owner_phone') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-Post</label>
                        <input type="text" class="form-control" name="owner_email" value="{{ old('owner_email') ?? $case->owner_email }}" placeholder="E-Post">
                        @if ($errors->has('owner_email'))
                            <span class="text-danger">{{ $errors->first('owner_email') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Lager posisjon *</label>
                        <select class="form-control" id="storage" name="storage" required>
                            <option selected disabled>Velg...</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ (old("storage") == $location->id ? "selected":"") }}>{{ $location->location_name }} - ({{ Str::limit($location->description, 30) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('storage'))
                            <span class="text-danger">{{ $errors->first('storage') }}</span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary mb-2 mt-3">Lagre og Oppdater</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Lukk</button>
            </div>
        </div>
    </div>
</div>
