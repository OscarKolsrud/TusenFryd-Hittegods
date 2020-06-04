<div class="card">
    <div class="card-header">

        Farger

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <div class="row container">
            <div class="mt-3 mb-3 float-left">
                <a class="btn btn-primary" href="{{ route('color_create_admin') }}" role="button">Opprett ny farge</a>
            </div>
        </div>

        @forelse ($colors as $color)
            @if($loop->first)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Fargenavn</th>
                        <th scope="col">Farge</th>
                        <th scope="col">Aktiv</th>
                        <th scope="col">Sist oppdatert</th>
                    </tr>
                    </thead>
                    <tbody>
                    @endif
                    <tr>
                        <th scope="row"><a href="{{ route('color_edit_admin', ['id' => $color->id]) }}">{{ $color->color }}</a></th>
                        <td><span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span></td>
                        <td>@if($color->visible == 1)<span class="badge badge-pill badge-success">Synlig</span> @else <span class="badge badge-pill badge-danger">Skjult</span>@endif</td>
                        <td>{{ date('H:i d.m.Y', strtotime($color->updated_at)) }}</td>
                    </tr>
                    @if($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <div class="text-center">
                <p>Det finnes ingen nåværende farger</p>
            </div>
        @endforelse

        <br>
        <div class="mt-3 float-left">
            <a class="btn btn-primary" href="{{ URL::previous() }}" role="button">Gå tilbake til forrige side</a>
        </div>
    </div>
</div>
