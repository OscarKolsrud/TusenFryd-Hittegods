@extends('layouts.app')

@section('template_title')
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">

                @include('panels.laf.listsearch')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
@endsection
