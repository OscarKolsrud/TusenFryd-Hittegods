<div class="card">
    <div class="card-header">

        Rediger farge

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>

    <div class="card-body">
        <form action="{{ route('color_edit_store_admin', ['id' => $color->id]) }}" method="post">
            @csrf
            <div class="form-group">
                <label for="color">Farge navn</label>
                <input type="text" name="color" class="form-control" id="color" placeholder="Fargenavn" value="{{ old('color') ?? $color->color }}">
            </div>
            <div class="form-group">
                <label for="class">Farge klasse</label>
                <select class="form-control" id="class" name="class">
                    <option value="">Ingen klasse (Bruk fargekoe)</option>
                    <option value="danger" {{ (old("class") == 'danger' || $color->class == 'danger' ? "selected":"") }}>danger (Rød)</option>
                    <option value="primary" {{ (old("class") == 'primary' || $color->class == 'primary' ? "selected":"") }}>primary (Blå)</option>
                    <option value="success" {{ (old("class") == 'success' || $color->class == 'success' ? "selected":"") }}>success (Grønn)</option>
                    <option value="warning" {{ (old("class") == 'warning' || $color->class == 'warning' ? "selected":"") }}>warning (Gul isj)</option>
                    <option value="info" {{ (old("class") == 'info' || $color->class == 'info' ? "selected":"") }}>info (Blå/turkis isj)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Fargekode (hex)</label>
                <input type="text" name="colorcode" class="form-control" id="colorcode" placeholder="Fargekode" value="{{ old('colorcode') ?? $color->colorcode }}">
            </div>
            <div class="form-group">
                <label for="visible">Synlighet</label>
                <select class="form-control" id="visible" name="visible">
                    <option value="1" {{ (old("visible") == '1' || $color->visible == '1' ? "selected":"") }}>Synlig</option>
                    <option value="0" {{ (old("visible") == '0' || $color->visible == '0' ? "selected":"") }}>Skjult</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Lagre endringer</button>
        </form>
        <hr>
        <div class="mt-3 float-left">
            <a class="btn btn-primary" href="{{ URL::previous() }}" role="button">Gå tilbake til forrige side</a>
        </div>
    </div>
</div>
