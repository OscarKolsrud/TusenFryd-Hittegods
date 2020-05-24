{{-- OOPS! This boilerplate requires some external JS --}}
@if(!$media->isEmpty())
    <div id="carouselCaseImage" class="carousel slide">
        <ol class="carousel-indicators">

            @foreach($media as $object)
                <li data-target="#carouselCaseImage" data-slide-to="{{ $loop->index }}" @if ($loop->first) class="active" @endif></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($media as $object)
                <div class="carousel-item @if ($loop->first)active @endif">
                    <img src="{{ $object->getUrl('displayversion') }}" class="d-block w-100" alt="Bilde">
                    <div class="carousel-caption d-none d-md-block">
                        <span class="badge badge-pill badge-dark">Lastet opp: {{ date('d.m.Y H:s', strtotime($object->created_at)) }}</span><br>
                        <div class="btn-group" role="group" aria-label="Bildehandlinger">
                            <a role="button" class="btn btn-sm btn-primary" target="_blank" href="{{ $object->getFullUrl() }}"><i class="fa Example of external-link fa-external-link" aria-hidden="true"></i> Åpne i ny fane</a>
                            @auth <button type="button" class="btn btn-sm  btn-danger" id="delete-{{ $object->getPath() }}" onclick="deleteImage('{{ $object->getPath() }}');" data-deleteurl="{{ URL::temporarySignedRoute('destroy_image', now()->addMinutes(60), ['reference' => $case->reference, 'image' => $object->id]) }}"><i class="fa fa-trash" aria-hidden="true"></i> Slett</button> @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselCaseImage" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Forrige</span>
        </a>
        <a class="carousel-control-next" href="#carouselCaseImage" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Neste</span>
        </a>
    </div>
@else
    <div class="text-center">
        <span class="font-weight-bold">Det finnes ingen bilder for øyeblikket</span>
    </div>
@endif

