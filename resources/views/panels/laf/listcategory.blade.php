<div class="card">
    <div class="card-header">

        Kategorier

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <div class="row container">
            <div class="mt-3 mb-3 float-left">
                <a class="btn btn-primary" href="{{ route('category_create_admin') }}" role="button">Opprett ny kategori</a>
            </div>
        </div>

        @forelse ($categories as $category)
            @if($loop->first)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Kategorinavn</th>
                        <th scope="col">Beskrivelse</th>
                        <th scope="col">Aktiv</th>
                        <th scope="col">Sist oppdatert</th>
                    </tr>
                    </thead>
                    <tbody>
                    @endif
                    <tr>
                        <th scope="row"><a href="{{ route('category_edit_admin', ['id' => $category->id]) }}">{{ $category->category_name }}</a></th>
                        <td>{{ Str::limit($category->description, 250) }}</td>
                        <td>@if($category->visible == 1)<span class="badge badge-pill badge-success">Synlig</span> @else <span class="badge badge-pill badge-danger">Skjult</span>@endif</td>
                        <td>{{ date('H:i d.m.Y', strtotime($category->updated_at)) }}</td>
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
