<div class="card">
    <div class="card-header">

        {{ $title ?? 'Resultat' }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <div class="mb-3 float-left">
            <a class="btn btn-primary" href="/home" role="button">Gå tilbake til forsiden/søkefelt</a>
        </div>

        <div class="mb-3 text-center">
            <form action="{{ route('get_search') }}" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Søkeord (F.eks: svart iphone)" name="query" id="searchQuery" value="{{ $query }}" aria-label="Søkeord" aria-describedby="searchButton">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" id="searchButton">Søk alle saker</button>
                    </div>
                </div>
            </form>
        </div>

        @forelse ($cases as $case)
            @if($loop->first)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Ref</th>
                        <th scope="col">Type</th>
                        <th scope="col">Gjenstand</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Beskrivelse</th>
                        <th scope="col">Farger</th>
                        <th scope="col">Mistet/funnet dato</th>
                    </tr>
                    </thead>
                    <tbody>
                    @endif
                    <tr>
                        @php
                            $sm = \Sebdesign\SM\Facade::get(\App\Models\Investigation::find($case->id), 'investigation');
                        @endphp
                        <th scope="row"><a href="{{ route('show_case', ['reference' => $case->reference]) }}">{{ $case->reference }}</a></th>
                        <td><span class="badge badge-{{ $sm->metadata('state', 'class_color') }} mr-1">{{ $sm->metadata('state', 'title') }}</span></td>
                        <td>{{ $case->item }}</td>
                        <td>{{ $case->category->category_name }}</td>
                        <td>{{ Str::limit($case->description, 100) }}</td>
                        <td>
                            @forelse ($case->colors as $color)
                                <span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span>
                                <br>
                            @empty
                                <span>Ingen farger registrert</span>
                            @endforelse
                        </td>
                        <td>{{ date('d.m.Y', strtotime($case->lost_date)) }}</td>
                    </tr>
                    @if($loop->last)
                    </tbody>
                </table>
                <small class="text-muted float-right">Viser {{ $cases->count() }} pr side | Totale treff {{ $cases->total() }}</small>
            @endif
        @empty
            <div class="text-center">
                <p>Det finnes ingen saker som passer søket</p>
            </div>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $cases->links() }}
        </div>

        <div class="mt-3 float-left">
            <a class="btn btn-primary" href="/home" role="button">Gå tilbake til forsiden/søkefelt</a>
        </div>
    </div>
</div>
