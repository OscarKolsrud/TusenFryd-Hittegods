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
        @forelse ($cases as $case)
            @if($loop->first)
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Ref</th>
                        <th scope="col">Gjenstand</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Beskrivelse</th>
                        <th scope="col">Mistet/funnet dato</th>
                    </tr>
                    </thead>
                    <tbody>
                    @endif
                    <tr>
                        <th scope="row"><a href="{{ route('show_case', ['reference' => $case->reference]) }}">{{ $case->reference }}</a></th>
                        <td>{{ $case->item }}</td>
                        <td>{{ $case->category->category_name }}</td>
                        <td>{{ Str::limit($case->description, 50) }}</td>
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
            <a class="btn btn-primary" href="{{ URL::previous() }}" role="button">Gå tilbake til forrige side</a>
        </div>
    </div>
</div>
