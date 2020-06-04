<div class="card">
    <div class="card-header">

        Lokasjoner

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <div class="row container">
            <div class="mt-3 mb-3 float-left">
                <a class="btn btn-primary" href="{{ route('location_create_admin') }}" role="button">Opprett ny lokasjon</a>
            </div>
        </div>

        @forelse ($locations as $location)
            @if($loop->first)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Lokasjonnavn</th>
                        <th scope="col">Beskrivelse</th>
                        <th scope="col">Aktiv</th>
                        <th scope="col">Sist oppdatert</th>
                    </tr>
                    </thead>
                    <tbody>
                    @endif
                    <tr>
                        <th scope="row"><a href="{{ route('location_edit_admin', ['id' => $location->id]) }}">{{ $location->location_name }}</a></th>
                        <td>{{ Str::limit($location->description, 250) }}</td>
                        <td>@if($location->visible == 1)<span class="badge badge-pill badge-success">Synlig</span> @else <span class="badge badge-pill badge-danger">Skjult</span>@endif</td>
                        <td>{{ date('H:i d.m.Y', strtotime($location->updated_at)) }}</td>
                    </tr>
                    @if($loop->last)
                    </tbody>
                </table>
            @endif
        @empty
            <div class="text-center">
                <p>Det finnes ingen nåværende kategorier</p>
            </div>
        @endforelse

        <br>
        <div class="mt-3 float-left">
            <a class="btn btn-primary" href="{{ URL::previous() }}" role="button">Gå tilbake til forrige side</a>
        </div>
    </div>
</div>
