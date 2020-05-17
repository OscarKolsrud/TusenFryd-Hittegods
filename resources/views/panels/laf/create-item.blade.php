<div class="card">
    <div class="card-header">

        Opprett Gjenstand

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
            <form action="{{ route('store_item') }}" method="post">
                @csrf
                <input type="text" name="reference" value="{{ Str::upper(Str::random(7)) }}" hidden>
                <div class="form-group row">
                    <label for="item" class="col col-form-label font-weight-bold">Gjenstand type*</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="item" name="item" placeholder="Gjenstand type (F.eks iphone 7 plus)" value="{{ old('item') }}" required>
                        @if ($errors->has('item'))
                            <span class="text-danger">{{ $errors->first('item') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category" class="col col-form-label font-weight-bold">Kategori*</label>
                    <div class="col-10">
                        <select class="form-control" id="category" name="category" required>
                            <option selected disabled>Velg...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ (old("category") == $category->id ? "selected":"") }}>{{ $category->category_name }} - ({{ Str::limit($category->description, 30) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
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
                        <input type="text" class="form-control" id="condition" name="condition" placeholder="Tilstanden til gjenstanden (Knust ol.)" value="{{ old('condition') }}">
                        @if ($errors->has('condition'))
                            <span class="text-danger">{{ $errors->first('condition') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="description" class="col col-form-label font-weight-bold">Beskrivelse*</label>
                    <div class="col-10">
                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Beskriv gjenstanden, inkluder gjerne bruksmerker, bakgrunner ol." rows="4" required>{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="date" class="col col-form-label font-weight-bold">Dato tapt*</label>
                    <div class="col-10">
                        <input class="form-control" id="date-lost" name="date" value="@if (old('date')){{ old('date') }}@else{{ date("d.m.Y") }}@endif">
                        @if ($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="location" class="col col-form-label font-weight-bold">Sted tapt</label>
                    <div class="col-10">
                        <input type="text" class="form-control" id="location" name="location" placeholder="F.eks Sirkusplassen, Rockburger eller SpinSpider" value="{{ old('location') }}" required>
                        @if ($errors->has('location'))
                            <span class="text-danger">{{ $errors->first('location') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="storage" class="col col-form-label font-weight-bold">Lager*</label>
                    <div class="col-10">
                        <select class="form-control" id="storage" name="storage" required>
                            <option selected disabled>Velg...</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ (old("storage") == $location->id ? "selected":"") }}>{{ $location->location_name }} - ({{ Str::limit($location->description, 30) }})</option>
                            @endforeach
                        </select>
                        @if ($errors->has('storage'))
                            <span class="text-danger">{{ $errors->first('storage') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="additional_info" class="col col-form-label font-weight-bold">Annen info</label>
                    <div class="col-10">
                        <textarea type="text" class="form-control" id="additional_info" name="additional_info" rows="2">{{ old('additional_info') }}</textarea>
                        @if ($errors->has('additional_info'))
                            <span class="text-danger">{{ $errors->first('additional_info') }}</span>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-2 float-right">Lagre og opprett Gjenstand</button>
            </form>
        </div>
</div>
