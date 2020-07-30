<div class="card">
    <div class="card-header">

        Rediger sak {{ $case->reference }}

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        <form action="/case/{{ $case->reference }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="item" class="col col-form-label font-weight-bold">Gjenstand type*</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="item" name="item" placeholder="Gjenstand type (F.eks iphone 7 plus)" value="{{ old('item') ?? $case->item }}" required>
                    @if ($errors->has('item'))
                        <span class="text-danger">{{ $errors->first('item') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="category_id" class="col col-form-label font-weight-bold">Kategori*</label>
                <div class="col-10">
                    <select class="form-control" id="category_id" name="category_id" required>
                        <option selected disabled>Velg...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (old("category_id") == $category->id || $case->category_id == $category->id ? "selected":"") }}>{{ $category->category_name }} - ({{ Str::limit($category->description, 30) }})</option>
                        @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        <span class="text-danger">{{ $errors->first('category_id') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="color" class="col col-form-label font-weight-bold">Farge(r)</label>
                <div class="col-10">
                    <select class="form-control" id="color" name="color[]" multiple="multiple">
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->color }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('color'))
                        <span class="text-danger">{{ $errors->first('color') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="condition" class="col col-form-label font-weight-bold">Tilstand</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="condition" name="condition" placeholder="Tilstand (Knust, ny etc.)" value="{{ old('condition') ?? $case->condition }}">
                    @if ($errors->has('condition'))
                        <span class="text-danger">{{ $errors->first('condition') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="description" class="col col-form-label font-weight-bold">Beskrivelse* <br><small>Interne notater: Benytt krøllparentes rundt f.eks {tekst}</small></label>
                <div class="col-10">
                    <textarea type="text" class="form-control" id="description" name="description" placeholder="Beskriv gjenstanden, inkluder gjerne bruksmerker, bakgrunner ol." rows="4" required>{{ old('description') ?? $case->description }}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="lost_date" class="col col-form-label font-weight-bold">Dato tapt*</label>
                <div class="col-10">
                    <input class="form-control" id="date-lost" name="lost_date" value="@if (old('lost_date')){{ old('lost_date') }}@elseif($case->lost_date){{ $case->lost_date }}@else{{ date("d.m.Y") }}@endif" required>
                    @if ($errors->has('lost_date'))
                        <span class="text-danger">{{ $errors->first('lost_date') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="lost_location" class="col col-form-label font-weight-bold">Sted tapt</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="lost_location" name="lost_location" placeholder="F.eks Sirkusplassen, Rockburger eller SpinSpider" value="{{ old('lost_location') ?? $case->lost_location }}">
                    @if ($errors->has('lost_location'))
                        <span class="text-danger">{{ $errors->first('lost_location') }}</span>
                    @endif
                </div>
            </div>
            <hr>
            @if($case->status == "found" || $case->status == "wait_for_delivery" || $case->status == "wait_for_police" || $case->status == "wait_for_send" || $case->status == "wait_for_pickup")
                <input type="text" name="require_locationpos" value="true" hidden>
                <div class="form-group row">
                    <label for="location_id" class="col col-form-label font-weight-bold">Lager pos*</label>
                    <div class="col-10">
                        <select class="form-control" id="location_id" name="location_id" required>
                            <option selected disabled>Velg...</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ (old("location_id") == $location->id || $case->location_id == $location->id ? "selected":"") }}>{{ $location->location_name }} - ({{ Str::limit($location->description, 30) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('location_id'))
                            <span class="text-danger">{{ $errors->first('location_id') }}</span>
                        @endif
                    </div>
                </div>
                <hr>
            @endif
            @if($case->status == "lost" || $case->status == "wait_for_delivery" || $case->status == "wait_for_police" || $case->status == "wait_for_send" || $case->status == "wait_for_pickup")
                <input type="text" name="require_names" value="true" hidden>
                <div class="form-group row">
                <label for="owner_name" class="col col-form-label font-weight-bold">Navn*</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Ola Nordmann" value="{{ old('owner_name') ?? $case->owner_name }}" required>
                    @if ($errors->has('owner_name'))
                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="owner_phone" class="col col-form-label font-weight-bold">Telefon**</label>
                <div class="col-10">
                    <input type="tel" class="form-control" id="owner_phone_input" name="owner_phone_input" value="{{ old('owner_phone') ?? $case->owner_phone }}">
                    <span class="text-success hide" id="valid-msg">Gyldig</span>
                    <span class="text-danger hide" id="error-msg"></span>
                    @if ($errors->has('owner_phone'))
                        <span class="text-danger">{{ $errors->first('owner_phone') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="owner_email" class="col col-form-label font-weight-bold">E-Post**</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="owner_email" name="owner_email" placeholder="ola@nordmann.com" value="{{ old('owner_email') ?? $case->owner_email }}">
                    @if ($errors->has('owner_email'))
                        <span class="text-danger">{{ $errors->first('owner_email') }}</span>
                    @endif
                </div>
            </div>
            <hr>
            @endif
            <div class="form-group row">
                <label for="additional_info" class="col col-form-label font-weight-bold">Annen info <br><small>F.eks Adresse</small></label>
                <div class="col-10">
                    <textarea type="text" class="form-control" id="additional_info" name="additional_info" rows="2">{{ old('additional_info') ?? $case->additional_info }}</textarea>
                    @if ($errors->has('additional_info'))
                        <span class="text-danger">{{ $errors->first('additional_info') }}</span>
                    @endif
                </div>
            </div>
            <hr>

            <a role="button" href="/case/{{ $case->reference }}" class="btn btn-primary mb-2 float-left">Gå tilbake</a>

            <button type="submit" class="btn btn-primary mb-2 float-right">Lagre endringer</button>
        </form>
    </div>
</div>
