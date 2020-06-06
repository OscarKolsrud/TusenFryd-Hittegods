<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sak {{ $case->reference }} | {{ config('app.name', Lang::get('titles.app')) }}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tel/intlTelInput.css') }}">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .container {
            max-width: 960px;
        }

        .lh-condensed { line-height: 1.25; }

        .iti { width: 100%; }

        .iti__flag {
            height: 15px;
            box-shadow: 0px 0px 1px 0px #888;
            background-image: url("{{ asset('images/tel/flags.png') }}");
            background-repeat: no-repeat;
            background-color: #DBDBDB;
            background-position: 20px 0; }
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("{{ asset('images/tel/flags@2x.png') }}"); } }

        .iti__flag.iti__np {
            background-color: transparent; }

        .hide {
            display: none; }

        .carousel .carousel-item img {
            max-height: 450px;
            min-width: auto;
            max-width: 450px;
            margin: auto;
        }
        .carousel {
            height: 450px;
        }

        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236c757d' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%236c757d' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="{{ asset('images/small_logo.png') }}" alt="Logo">
        <h2>Din etterlysning: {{ $case->reference }}</h2>
        @if($statemachine->metadata('state', 'resolution'))
            <span class="text-danger font-weight-bold"><i class="fa fa-lock" aria-hidden="true"></i> Låst for redigering</span>
        @endif
        <div class="mb-3">
            <span class="font-weight-bold">Nåværende status: </span><span class="badge badge-{{ $statemachine->metadata('state','class_color') }}">{{ $statemachine->metadata('state','title') }}</span><br>
        </div>
        <p class="lead">Under finner du hva vi har registrert om deg, og gjenstanden du har mistet. For å hjelpe oss å finne gjenstanden din kan du også <b>laste opp bilder</b> og <b>sende oss meldinger</b>. Meldinger kan ha <b>lang behandlingstid</b>, og det hjelper <b>ikke</b> sende flere.</p>
    </div>

    @include('partials.form-status')

    <div class="row">
        <div class="col-md-12 order-md-1">
            <h4 class="mb-3 font-weight-bold">Etterlysning</h4>
            <h5>Gjenstand</h5>
            <p>{{ $case->item ?? 'Ikke spesifisert' }}</p>
            <hr>
            <h5>Beskrivelse</h5>
            <p>{{ preg_replace("/{([^}]*)}/","",$case->description) ?? 'Ikke spesifisert' }}</p>
            <hr>
            <h5>Annen informasjon/notater</h5>
            <p>{{ preg_replace("/{([^}]*)}/","",$case->additional_info) ?? 'Ikke spesifisert' }}</p>
            <hr>
            <h5>Kategori</h5>
            <p>{{ $case->category->category_name ?? 'Ikke spesifisert' }} ({{ $case->category->description ?? 'Ingen beskrivelse' }})</p>
            <hr>
            <h5>Farge(r)</h5>
            @forelse ($case->colors as $color)
                <span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span>
            @empty
                <span>Ingen spesifisert</span>
            @endforelse
            <hr>
            <h5>Bilder</h5>
            <div class="mb-3">
                @include('panels.laf.imagecarousel')
                <br>
                @if(!$statemachine->metadata('state', 'resolution'))
                    <form action="{{ URL::temporarySignedRoute('store_images', now()->addMinutes(60), ['reference' => $case->reference]) }}" method="post" class="dropzone" id="image-dropzone" enctype="multipart/form-data">
                        @csrf
                        <div class="dz-message needsclick">
                            <span class="font-weight-bold">Dra filer hit eller klikk for å laste opp</span><br>
                            <span class="font-weight-bold">Maksimalt 5 filer pr. opplastning</span>
                        </div>

                        <input type="text" name="from_guest" value="true" required hidden>
                    </form>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary mb-2" id="submitImages">Last opp de valgte bildene</button>
                    </div>
                @endif
            </div>
            <h4 class="mb-3 font-weight-bold">Meldinger</h4>
            <form action="{{ URL::temporarySignedRoute('public_case_message', now()->addMinutes(60), ['reference' => $case->reference, 'lost_date' => $case->lost_date]) }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="message" class="font-weight-bold h5">Skriv ny melding</label>
                    <textarea class="form-control" id="message" name="message" rows="3" @if($statemachine->metadata('state', 'resolution')) disabled @endif>@if($statemachine->metadata('state', 'resolution')) Nye meldinger er ikke mulig fordi saken er ansett som ferdig @endif</textarea>
                </div>
                <div class="form-group">
                    <div class="btn-group float-right" role="group" aria-label="Flere valg">
                        <button type="submit" class="btn btn-primary mb-2" @if($statemachine->metadata('state', 'resolution')) disabled @endif>Send melding</button>
                    </div>
                </div>
            </form>
            <h4 class="mb-3 mt-5 font-weight-bold">Meldingshistorikk</h4>
            @forelse ($messages as $conversation)
                <div>
                    @if($conversation->from_guest && $conversation->messagetype == 'message')
                        <span class="font-weight-bold mb-2"><i class="fa fa-comments" aria-hidden="true"></i> Gjest (Fra deg)</span><br>
                        <span>{{ $conversation->message }}</span><br>
                    @elseif(!$conversation->from_guest && $conversation->messagetype == 'message')
                        <span class="font-weight-bold mb-2"><i class="fa fa-comment" aria-hidden="true"></i> {{ $conversation->user->first_name }} - Gjesteservice Tusenfryd</span> <br>
                        <span>{{ $conversation->message }}</span><br>
                    @else
                        <span class="font-weight-bold mb-2"><i class="fa fa-bullhorn" aria-hidden="true"></i>{{ $conversation->message }}</span><br>
                    @endif
                    <small class="font-italic">Tidspunkt: {{ date('H:s d.m.Y', strtotime($conversation->created_at)) }}</small>
                    <hr>
                </div>
            @empty
                <div>
                    <span>Ingen meldinger tilgjengelig</span>
                </div>
                <hr>
            @endforelse

            <div class="mt-3 d-flex justify-content-center">
                {{ $messages->links() }}
            </div>

            <h4 class="mb-3 font-weight-bold">Kontaktdetaljer</h4>

            <form action="{{ URL::temporarySignedRoute('public_case_edit_contact', now()->addMinutes(60), ['reference' => $case->reference, 'lost_date' => $case->lost_date]) }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="owner_name">Navn <span class="text-muted">(Kreves)</span></label>
                    <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Ola Nordmann" value="{{ $case->owner_name }}" required>
                    @if ($errors->has('owner_name'))
                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="owner_email">E-Post <span class="text-muted">(Kreves om Telefonnummer mangler)</span></label>
                    <input type="email" class="form-control" id="owner_email" name="owner_email" placeholder="ola@nordmann.com" value="{{ $case->owner_email }}">
                    @if ($errors->has('owner_email'))
                        <span class="text-danger">{{ $errors->first('owner_email') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="owner_phone_input">Telefonnummer <span class="text-muted">(Kreves om E-Post mangler)</span></label><br>
                    <input type="tel" class="form-control" id="owner_phone_input" name="owner_phone_input">
                    <span class="text-success hide" id="valid-msg">Gyldig</span>
                    <span class="text-danger hide" id="error-msg"></span>
                    @if ($errors->has('owner_phone'))
                        <span class="text-danger">{{ $errors->first('owner_phone') }}</span>
                    @endif
                </div>


                <div class="text-center">
                    <button class="btn btn-primary btn-lg btn-lg" type="submit">Lagre endringer i kontaktinformasjon</button>
                </div>
            </form>
            <hr class="mb-3">
            <div class="text-center">
                <span>Vil du slette hva vi har lagret om deg og dermed avslutte saken?</span><br>
                <small class="text-muted">En slik sletting kan ikke omgjøres og prosesseres umiddelbart</small><br>
                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteconfirmModal">Slett min personlige info og avslutt saken</button>
            </div>
        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; {{ date('Y') }} TusenFryd. Alle rettigheter reservert</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a target="_blank" href="https://www.tusenfryd.no/policy-beskyttelse-av-personopplysninger">Personvern</a></li>
        </ul>
    </footer>
</div>


<!-- Delete confirmation modal -->
<div class="modal fade" id="deleteconfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteconfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteconfirmModalLabel">Bekreft sletting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                Er du sikker på at du vil slette denne saken permanent?<br>
                En slik sletting vil bety at vi ikke lenger vil kunne ta kontakt med deg om vi finner din savnede gjenstand.<br>
                En sletting kan <span class="font-weight-bold">ikke</span> omgjøres.
            </div>
            <form id="delete-form" action="{{ URL::temporarySignedRoute('public_case_delete', now()->addMinutes(60), ['reference' => $case->reference, 'lost_date' => $case->lost_date]) }}" method="POST" style="display: none;">
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
<script src="{{ asset('js/dropzone.min.js') }}"></script>
<script src="{{ asset('js/tel/intlTelInput.min.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script>
    window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};

    function deletecase() {
        event.preventDefault();
        document.getElementById('delete-form').submit();
    }

    var input = document.querySelector("#owner_phone_input"),
        errorMsg = document.querySelector("#error-msg"),
        validMsg = document.querySelector("#valid-msg");

    // here, the index maps to the error code returned from getValidationError - see readme
    var errorMap = ["Ugyldig telefonnummer", "Ugyldig landskode", "For kort", "For langt", "Ugyldig telefonnummer"];

    // initialise plugin
    var iti = window.intlTelInput(input, {
        utilsScript: "{{ asset('js/tel/utils.js') }}",
        hiddenInput: "owner_phone",
        separateDialCode: true,
        initialCountry: "NO",
    });

    iti.setNumber("{{ old('owner_phone') ?? $case->owner_phone }}");

    var reset = function() {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };

    // on blur: validate
    input.addEventListener('blur', function() {
        reset();
        if (input.value.trim()) {
            if (iti.isValidNumber()) {
                validMsg.classList.remove("hide");
            } else {
                input.classList.add("error");
                var errorCode = iti.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.remove("hide");
            }
        }
    });

    // on keyup / change flag: reset
    input.addEventListener('change', reset);
    input.addEventListener('keyup', reset);


    function deleteImage(id) {
        var linkref = document.getElementById('delete-' + id);

        var deleteUrl = linkref.dataset.deleteurl;

        axios.delete(deleteUrl, {})
            .then(function (response) {
                if(response.status) {
                    alert('Bildet ble slettet og vil innen kort tid forsvinne fra serveren. Du vil ikke se endringene før du laster siden på nytt.')
                } else {
                    alert('Bildet kunne ikke slettes, prøv igjen senere');
                    console.log(response);
                }
            })
            .catch(function (error) {
                alert('Bildet kunne ikke slettes, prøv igjen senere');
                console.log(error);
            });
    }
</script>

<script>
    Dropzone.options.imageDropzone = { // The camelized version of the ID of the form element

        // The configuration we've talked about above
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,

        // The setting up of the dropzone
        init: function() {
            var myDropzone = this;

            // First change the button to actually tell Dropzone to process the queue.
            document.getElementById("submitImages").addEventListener("click", function(e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();
                myDropzone.processQueue();
            });

            // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
            // of the sending event because uploadMultiple is set to true.
            this.on("sendingmultiple", function() {
                document.getElementById("submitImages").disabled = true;
            });
            this.on("successmultiple", function(files, response) {
                alert('Filene ble lastet opp');
                location.reload();
            });
            this.on("errormultiple", function(files, response) {
                alert('Filene kunne ikke lastes opp, prøv igjen senere');
                document.getElementById("submitImages").disabled = false;
            });
        }

    }

</script>
</html>
