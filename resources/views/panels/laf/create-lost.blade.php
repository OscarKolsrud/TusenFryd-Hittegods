<div class="card">
    <div class="card-header">

        Opprett Etterlysning

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        <form action="{{ route('store_lost') }}" method="post">
            @csrf
            <input type="text" name="reference" value="E{{ Str::upper(Str::random(6)) }}" hidden>
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
                <label for="description" class="col col-form-label font-weight-bold">Beskrivelse* <br><small>Interne notater: Benytt krøllparentes rundt f.eks {tekst}</small></label>
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
            <hr>
            <div class="form-group row">
                <label for="owner_name" class="col col-form-label font-weight-bold">Navn*</label>
                <div class="col-10">
                    <input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="Ola Nordmann" value="{{ old('owner_name') }}" required>
                    @if ($errors->has('owner_name'))
                        <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label for="owner_phone" class="col col-form-label font-weight-bold">Telefon**</label>
                <div class="col-10">
                    <input type="tel" class="form-control" id="owner_phone_input" name="owner_phone_input" value="{{ old('owner_phone') }}">
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
                    <input type="text" class="form-control" id="owner_email" name="owner_email" placeholder="ola@nordmann.com" value="{{ old('owner_email') }}">
                    @if ($errors->has('owner_email'))
                        <span class="text-danger">{{ $errors->first('owner_email') }}</span>
                    @endif
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <label for="additional_info" class="col col-form-label font-weight-bold">Annen info <br><small>F.eks Adresse</small></label>
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
