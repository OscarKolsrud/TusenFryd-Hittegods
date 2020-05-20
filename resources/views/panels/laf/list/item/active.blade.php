<div class="card">
    <div class="card-header">

        Gjenstander

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
                        <th scope="col">Beskrivelse</th>
                        <th scope="col">Mistet/funnet dato</th>
                    </tr>
                    </thead>
                    <tbody>
            @endif
            <tr>
                <th scope="row"><a href="#">{{ $case->reference }}</a></th>
                <td>{{ $case->item }}</td>
                <td>{{ Str::limit($case->description, 50) }}</td>
                <td>{{ date('d.m.Y', strtotime($case->lost_date)) }}</td>
            </tr>
            @if($loop->last)
                </tbody>
                </table>
                <small class="text-muted float-right">Viser {{ $cases->count() }} pr side | Totale treff {{ $cases->total() }}</small>
            @endif
        @empty
            <p>Det finnes ingen saker som passer søket</p>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $cases->links() }}
        </div>
    </div>
</div>
