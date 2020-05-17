<div class="card">
    <div class="card-header">

        Redigeringshistorikk {{ $case->reference }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Tidspunkt</th>
                <th scope="col">Av</th>
                <th scope="col">Gammel data</th>
                <th scope="col">Ny data</th>
            </tr>
            </thead>
            <tbody>
        @forelse($case->audits as $audit)
                <tr>
                    <th scope="row">{{ date('d.m.Y H:s', strtotime($audit->created_at)) }}</th>
                    <td>{{ $audit->tags }}</td>
                    <td>
                        @forelse($audit->old_values as $key => $value)
                            <span class="font-weight-bold">{{ $key }}: </span><span>{{ $value }}</span><br>
                        @empty
                            Ingen
                        @endforelse
                    </td>
                    <td>
                        @forelse($audit->new_values as $key => $value)
                            <span class="font-weight-bold">{{ $key }}: </span><span>{{ $value }}</span><br>
                        @empty
                            Ingen
                        @endforelse
                    </td>
                </tr>
        @empty
            <tr>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
        @endforelse
            </tbody>
        </table>
            <div class="mt-3">
                <a role="button" class="btn btn-primary" href="{{ url('case/' . $case->reference) }}">Gå tilbake</a>
            </div>
    </div>
</div>
