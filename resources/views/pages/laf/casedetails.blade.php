@extends('layouts.app')

@section('template_title')
    Sak {{ $case->reference }}
@endsection

@section('template_fastload_css')
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

    iframe{
    overflow:hidden;
    }

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
@endsection

@section('content')
    @include('panels.laf.casedetails')
@endsection

@section('footer_scripts')
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tel/intlTelInput.css') }}">
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/tel/intlTelInput.min.js') }}"></script>

    <script>
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

        iti.setNumber("{{ old('owner_phone') ?? $case->owner_phone}}");

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

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        function deletecase() {
            event.preventDefault();
            document.getElementById('delete-form').submit();
        }

        function markProcessed(id, doall) {
            if(doall) {
                axios.post('{{ $case->reference }}/readconversation', {
                    case: id,
                    all: true,
                })
                    .then(function (response) {
                        if(response.status) {
                            document.getElementById("messageProcess-" + id).disabled = true;
                        } else {
                            alert('Kunne ikke merke som lest');
                        }
                    })
                    .catch(function (error) {
                        alert('Kunne ikke merke som lest: ' + error);
                        console.log(error);
                    });
            } else {
                axios.post('{{ $case->reference }}/readconversation', {
                    message: id,
                })
                    .then(function (response) {
                        if(response.status) {
                            document.getElementById("messageProcess-" + id).disabled = true;
                        } else {
                            alert('Kunne ikke merke som lest');
                        }
                    })
                    .catch(function (error) {
                        alert('Kunne ikke merke som lest: ' + error);
                        console.log(error);
                    });
            }
        }

        function redirectToLink() {
            var linkref = document.getElementById("link-reference").value;

            axios.get(linkref + '/checkalive', {})
                .then(function (response) {
                    if(response.status) {
                        window.location.href = '{{ $case->reference }}/link/' + linkref;
                    } else {
                        alert('Fant ingen sak med denne referansen');
                    }
                })
                .catch(function (error) {
                    alert('Fant ingen sak med denne referansen');
                    console.log(error);
                });
        }

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
@endsection
