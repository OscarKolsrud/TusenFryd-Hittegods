<div class="card">
    <div class="card-header">

        Opprett lokasjon

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <form action="{{ route('location_store_admin') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="location_name">Lokasjon navn</label>
                <input type="text" name="location_name" class="form-control" id="location_name" placeholder="Navn" value="{{ old('location_name') }}">
            </div>
            <div class="form-group">
                <label for="description">Lokasjon beskrivelse</label>
                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="visible">Synlighet</label>
                <select class="form-control" id="visible" name="visible">
                    <option value="1" {{ (old("visible") == '1' ? "selected":"") }}>Synlig</option>
                    <option value="0" {{ (old("visible") == '0' ? "selected":"") }}>Skjult</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Opprett</button>
        </form>
        <hr>
        <div class="mt-3 float-left">
            <a class="btn btn-primary" href="{{ URL::previous() }}" role="button">Gå tilbake til forrige side</a>
        </div>
    </div>
</div>
