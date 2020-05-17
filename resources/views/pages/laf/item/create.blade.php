@extends('layouts.app')

@section('template_title')
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">

                @include('panels.laf.create-item')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2/select2-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">

    <script src="{{ asset('js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('js/select2/nb.js') }}"></script>
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/locales/bootstrap-datepicker.no.min.js') }}"></script>

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
