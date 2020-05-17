@forelse ($data as $d)
    <div class="card mt-3 mb-3">
        <a href="{{ route('show_case', ['reference' => $d["reference"]]) }}">
            <div class="card-body row">
                <div class="col-9">
                    <span class="badge badge-secondary mr-1">{{ \Sebdesign\SM\Facade::get(\App\Models\Investigation::find($d["id"]), 'investigation')->metadata('state', 'title') }}</span><span class="font-weight-bold">{{ $d["item"] }} (K: {{ \App\Models\Category::find($d["category_id"])->category_name ?? 'Ingen kategori' }})</span><br>
                    @forelse ($d["colors"] as $color)
                        <span>{{ $color["color"] }} <i class="fa fa-circle @if($color["class"])text-{{ $color["class"] }}@endif" @if($color["colorcode"] && !$color["class"])style="color:{{ $color["colorcode"] }};" @endif aria-hidden="true"></i></span>
                    @empty
                        <span>Ingen farger registrert</span>
                    @endforelse <br>
                    <small class="text-muted">Dato mistet: {{ date('d.m.Y', strtotime($d["lost_date"])) }} ⋅ Sist oppdatert: {{ date('d.m.Y H:s', strtotime($d["updated_at"])) }}</small>
                </div>
                <div class="col-3">
                    <span class="font-weight-bold">Referanse:</span> {{ $d["reference"] }}<br>
                    <span class="font-weight-bold">Eier:</span> {{ $d["owner_name"] ?? 'Ikke tilgjengelig' }}<br>
                    <span class="font-weight-bold">Lager pos:</span> {{ \App\Models\Location::find($d["location_id"])->location_name ?? 'Ikke tilgjengelig' }}
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="text-center">
        Ingen resultater funnet med det søkeordet
    </div>
@endforelse
