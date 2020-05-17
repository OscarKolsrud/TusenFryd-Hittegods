@extends('layouts.app')

@section('template_title')
@endsection

@section('template_fastload_css')
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

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">

                @include('panels.laf.create-lost')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2/select2-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tel/intlTelInput.css') }}">

    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/select2/nb.js') }}"></script>
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/locales/bootstrap-datepicker.no.min.js') }}"></script>
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
        $(document).ready(function() {
            $('#color').select2({
                theme: 'bootstrap4',
            });

            @if(old('color'))
                @foreach(old('color') as $color)
                $('#color').val('{{ $color}}');
                @endforeach
            @endif
                $('#color').trigger('change'); // Notify any JS components that the value changed

            //init the datepicker
            $('#date-lost').datepicker({
                format: 'dd.mm.yyyy',
                endDate: '+0d',
                language: 'no'
            });

        });
    </script>
@endsection
