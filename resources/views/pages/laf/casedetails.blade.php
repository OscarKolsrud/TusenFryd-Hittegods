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
@endsection

@section('content')
    @include('panels.laf.casedetails')
@endsection

@section('footer_scripts')
    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/basic.css') }}">
    <script src="{{ asset('js/dropzone.min.js') }}"></script>

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
            //TODO: FIX THIS!
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
