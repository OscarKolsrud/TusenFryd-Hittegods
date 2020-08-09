<div class="card">
    <div class="card-header">

        Statistikk

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        <div class="mt-3 text-center">
        <h4>Saks statistikk</h4>
        </div>
            <table class="mt-3 table table-hover w-100">
                <thead>
                <tr>
                    <th scope="col">Forklaring</th>
                    <th scope="col">Verdi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Totalt antall saker</th>
                    <td>{{ $total->count() }}</td>
                </tr>
                <tr>
                    <th scope="row">Antall saker du har registrert</th>
                    <td>{{ $thisuser_case }}</td>
                </tr>
                <tr>
                    <th scope="row">Antall saker med "Registrert tapt (Etterlysning)" eller "Registrert mistet (Gjenstand)" status</th>
                    <td>{{ ($lost+$found) }}</td>

                </tr>
                <tr>
                    <th scope="row">Antall prosent med "Registrert tapt (Etterlysning)" eller "Registrert mistet (Gjenstand)" status</th>
                    <td>{{ number_format((float)((($lost+$found)/$total->count())*100), 2, '.', '') }}%</td>
                </tr>
                <tr>
                    <th scope="row">Antall saker som er "på vent"</th>
                    <td>{{ ($wait_for_police+$wait_for_delivery+$wait_for_send+$wait_for_pickup) }}</td>

                </tr>
                <tr>
                    <th scope="row">Antall prosent av saker som er "på vent"</th>
                    <td>{{ number_format((float)((($wait_for_police+$wait_for_delivery+$wait_for_send+$wait_for_pickup)/$total->count())*100), 2, '.', '') }}%</td>
                </tr>
                <tr>
                    <th scope="row">Antall saker som er avsluttet uten gjenforening</th>
                    <td>{{ ($canceled+$evicted+$police) }}</td>

                </tr>
                <tr>
                    <th scope="row">Antall prosent av saker som er avsluttet uten gjenforening</th>
                    <td>{{ number_format((float)((($canceled+$evicted+$police)/$total->count())*100), 2, '.', '') }}%</td>
                </tr>
                <tr>
                    <th scope="row">Antall saker som er avsluttet med gjenforening av gjenstand til gjest</th>
                    <td>{{ ($picked_up+$sent) }}</td>

                </tr>
                <tr>
                    <th scope="row">Antall prosent av saker som er avsluttet med gjenforening av gjenstand til gjest</th>
                    <td>{{ number_format((float)((($picked_up+$sent)/$total->count())*100), 2, '.', '') }}%</td>
                </tr>
                </tbody>
            </table>

        <div id="donutchart" class="d-flex justify-content-center" style="width: 900px; height: 500px;"></div>

        <div class="mt-3 text-center">
            <h4>Meldings statistikk</h4>
        </div>
        <table class="mt-3 table table-hover w-100">
            <thead>
            <tr>
                <th scope="col">Forklaring</th>
                <th scope="col">Verdi</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">Totalt antall meldinger sendt (Fra gjester og ansatte)</th>
                <td>{{ $messagetotal }}</td>

            </tr>
            <tr>
                <th scope="row">Totalt antall meldinger fra gjester</th>
                <td>{{ $message_fromguest }}</td>

            </tr>
            <tr>
                <th scope="row">Totalt antall du har sendt til gjester</th>
                <td>{{ $thisuser_message }}</td>

            </tr>
            </tbody>
        </table>

        <div class="mt-3 text-center">
            <h4>Media statistikk</h4>
        </div>
        <table class="mt-3 table table-hover w-100">
            <thead>
            <tr>
                <th scope="col">Forklaring</th>
                <th scope="col">Verdi</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">Antall opplastede bilder</th>
                <td>{{ $media->count() }}</td>

            </tr>
            </tbody>
        </table>
    </div>
</div>
