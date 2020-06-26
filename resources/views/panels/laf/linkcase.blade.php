<div class="card">
    <div class="card-header">

        Sammenlign og slå sammen

        @role('admin', true)
        <span class="pull-right badge badge-primary" style="margin-top:4px">
                Administrator
            </span>
        @endrole

    </div>
    <div class="card-body">
        @if($compare_only)
            <div class="alert alert-info" role="alert">
                Siden disse sakene startet med samme status er kun sammenligning mulig
            </div>
        @endif
        @if(!$compare_only)<form id="link-form" action="{{ url('case/' . $case1->reference . '/link/' . $case2->reference) }}" method="post">
            @csrf
            @endif
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Felt</th>
                    @if(!$compare_only)<th scope="col">Gjenstand</th>@else<th scope="col">Sak 1</th>@endif
                    @if(!$compare_only)<th scope="col">Etterlysning</th>@else<th scope="col">Sak 2</th>@endif
                    @if(!$compare_only)<th scope="col">Etter sammenslåing</th>@endif
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Referanse</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->reference }}" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->reference }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <select class="form-control" name="reference" id="reference">
                                    <option value="{{ $case1->reference }}" {{ (old("reference") == $case1->reference ? "selected":"") }}>{{ $case1->reference }}</option>
                                    <option value="{{ $case2->reference }}" {{ (old("reference") !== $case2->reference ? "selected":"") }}>{{ $case2->reference }}</option>
                                </select>
                                @if ($errors->has('reference'))
                                    <span class="text-danger">{{ $errors->first('reference') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Gjenstand</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->item }}" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->item }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" name="item" value="{{ old('item') ?? $case1->item ?? $case2->item }}" required>
                                @if ($errors->has('item'))
                                    <span class="text-danger">{{ $errors->first('item') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Beskrivelse<br><small>Tips: Dra i høyre<br>hjørne for å gjøre<br>feltene større</small></th>
                    <td>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" disabled>{{ $case1->description }}</textarea>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" disabled>{{ $case2->description }}</textarea>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <textarea class="form-control" name="description" rows="4" required>{{ old('description') ?? $case1->description ?? $case2->description }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Farge(r)<br><small><span class="font-weight-bold">OBS!</span> Fylles ikke i<br>automatisk</small></th>
                    <td>
                        @forelse ($case1->colors as $color)
                            <span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span>
                        @empty
                            <span>Ingen registrert</span>
                        @endforelse
                    </td>
                    <td>
                        <div class="form-group">
                            @forelse ($case2->colors as $color)
                                <span>{{ $color->color }} <i class="fa fa-circle @if($color->class)text-{{ $color->class }}@endif" @if($color->colorcode && !$color->class)style="color:{{ $color->colorcode }};" @endif aria-hidden="true"></i></span>
                            @empty
                                <span>Ingen registrert</span>
                            @endforelse
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <select class="form-control" id="color" name="color[]" multiple="multiple">
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}" >{{ $color->color }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('color'))
                                    <span class="text-danger">{{ $errors->first('color') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Tilstand</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->condition }}" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->condition }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" name="condition" value="{{ old('condition') ?? $case1->condition ?? $case2->condition }}">
                                @if ($errors->has('condition'))
                                    <span class="text-danger">{{ $errors->first('condition') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Mistet pos</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->lost_location }}" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->lost_location }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" name="lost_location" value="{{ old('lost_location') ?? $case1->lost_location ?? $case2->lost_location }}">
                                @if ($errors->has('lost_location'))
                                    <span class="text-danger">{{ $errors->first('lost_location') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Funnet dato</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ date('d.m.Y', strtotime($case1->lost_date)) }}" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ date('d.m.Y', strtotime($case2->lost_date)) }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="date-lost" name="date" required value="{{ old('lost_date') ?? date('d.m.Y', strtotime($case1->lost_date)) ?? date('d.m.Y', strtotime($case2->lost_date)) }}">
                                @if ($errors->has('lost_date'))
                                    <span class="text-danger">{{ $errors->first('lost_date') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Kategori</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->category->category_name ?? 'Ingen kategori' }} - ({{ Str::limit($case1->category->description, 30) }})" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->category->category_name ?? 'Ingen kategori' }} - ({{ Str::limit($case2->category->description, 30) }})" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <select class="form-control" name="category" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (old("category") || $case1->category->id || $case2->category->id == $category->id ? "selected":"") }}>{{ $category->category_name }} - ({{ Str::limit($category->description, 30) }})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category'))
                                    <span class="text-danger">{{ $errors->first('category') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Lager pos</th>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case1->location->location_name ?? 'Ingen lagerposisjon' }} - ({{ Str::limit($case1->category->description, 30) }})" disabled>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->location->location_name ?? 'Ingen lagerposisjon' }} - ({{ Str::limit($case2->category->description, 30) }})" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <select class="form-control" name="location" required>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ (old("location_id") || $case1->location->id == $location->id ? "selected":"") }}>{{ $location->location_name }} - ({{ Str::limit($location->description, 30) }})</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('location_id'))
                                    <span class="text-danger">{{ $errors->first('location_id') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Eier navn</th>
                    <td>
                        <div class="form-group">
                            N/A
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->owner_name }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" name="owner_name" required value="{{ old('owner_name') ?? $case2->owner_name }}">
                                @if ($errors->has('owner_name'))
                                    <span class="text-danger">{{ $errors->first('owner_name') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Eier Tlf</th>
                    <td>
                        <div class="form-group">
                            N/A
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->owner_phone }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="tel" class="form-control" id="owner_phone_input" name="owner_phone_input">
                                <span class="text-success hide" id="valid-msg">Gyldig</span>
                                <span class="text-danger hide" id="error-msg"></span>
                                @if ($errors->has('owner_phone'))
                                    <span class="text-danger">{{ $errors->first('owner_phone') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Eier E-Post</th>
                    <td>
                        <div class="form-group">
                            N/A
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $case2->owner_email }}" disabled>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" name="owner_email" value="{{ old('owner_email') ?? $case2->owner_email }}">
                                @if ($errors->has('owner_email'))
                                    <span class="text-danger">{{ $errors->first('owner_email') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                <tr>
                    <th scope="row">Annen info<br><small>Tips: Dra i høyre<br>hjørne for å gjøre<br>feltene større</small></th>
                    <td>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" disabled>{{ $case1->additional_info }}</textarea>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" disabled>{{ $case2->additional_info }}</textarea>
                        </div>
                    </td>
                    @if(!$compare_only)
                        <td>
                            <div class="form-group">
                                <textarea class="form-control" name="additional_info" rows="4" required>{{ old('additional_info') ?? $case1->additional_info . " {(Systemnotat: Denne saken stammer fra en sammenslåing mellom sak " . $case1->reference . " og sak " . $case2->reference . ")}" ?? $case2->additional_info . " {(Systemnotat: Denne saken stammer fra en sammenslåing mellom sak " . $case1->reference . " og sak " . $case2->reference . ")}" }}</textarea>
                                @if ($errors->has('additional_info'))
                                    <span class="text-danger">{{ $errors->first('additional_info') }}</span>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
                </tbody>
            </table>
            <hr>
            <div class="mt-3 mb-3">
                <a role="button" class="btn btn-primary float-left" href="{{ url('case/' . $case1->reference) }}">Avbryt og gå tilbake til sak {{ $case1->reference }}</a>
                @if(!$compare_only)
                    <button type="submit" class="btn btn-primary float-right">Lagre og slå sammen</button>
                    <br><br>
                    <small class="float-right">Denne handlingen kan <span class="font-weight-bold">ikke</span> reverseres/angres,
                        <br>Og vil <span class="font-weight-bold">ikke</span> generere et varsel til Gjest</small>
        </form>
        @endif
    </div>
</div>
</div>
