@extends('layouts.app')

@section('template_title')
@endsection

@section('template_fastload_css')
@endsection

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">

                @include('panels.welcome-panel')

            </div>
        </div>
    </div>

@endsection

@section('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/@meilisearch/instant-meilisearch/dist/instant-meilisearch.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4"></script>

    <script>
        //Instantsearch
        const search = instantsearch({
            indexName: "investigations",
            searchClient: instantMeiliSearch(
                "https://hittegods-search.tusenfryd.no",
                "72c6e352299c65066ce76b2785cb72f0aad3b106bdd36e276e45374b98454360",
            ),
            searchFunction(helper) {
                if (helper.state.query) {
                    helper.search();
                }
            },
        });

        search.addWidgets([
            instantsearch.widgets.refinementList({
                container: '#refinement-list',
                attribute: 'initial_status',
            }),

            instantsearch.widgets.searchBox({
                container: '#searchbox',
                placeholder: 'Søk etter saker...',
                queryHook(query, search) {
                    if(query.length > 2) {
                        search(query);
                    }
                },
            }),

            instantsearch.widgets.hits({
                container: '#hits',
                templates: {
                    item: `
                    <h2>@{{#helpers.highlight}}{ "attribute": "reference" }@{{/helpers.highlight}}</h2>
                            @{{#helpers.highlight}}{ "attribute": "description" }@{{/helpers.highlight}}
                    `,
                },
            }),

            instantsearch.widgets.pagination({
                container: '#pagination',
            })
        ]);

        // Create the render function
        const renderHits = (renderOptions, isFirstRender) => {
            const { hits, widgetParams } = renderOptions;
            
            widgetParams.container.innerHTML = `

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Ref</th>
                <th scope="col">Type</th>
                <th scope="col">Gjenstand</th>
                <th scope="col">Kategori</th>
                <th scope="col">Beskrivelse</th>
                <th scope="col">Farger</th>
                <th scope="col">Mistet/funnet dato</th>
            </tr>
            </thead>
            <tbody>
      ${hits
                .map(
                    item =>
                        `
<th scope="row"><a href="/case/${item.reference}">${instantsearch.highlight({ attribute: 'reference', hit: item })}</a></th>
<td>${returnStatusBadge(item.status)}</td>
<td>${instantsearch.highlight({ attribute: 'item', hit: item })}</td>
<td>${instantsearch.highlight({ attribute: 'category', hit: item })}</td>
<td>${instantsearch.highlight({ attribute: 'description', hit: item })}</td>
<td>${returnColors(item.colors)}</td>
<td>${formatDate(item.lost_date)}</td>
`
                )
                .join('')}
                </tbody>
        </table>
  `;
        };

        function formatDate (input) {
            var datePart = input.match(/\d+/g),
                year = datePart[0].substring(2), // get only two digits
                month = datePart[1], day = datePart[2];

            return day+'.'+month+'.'+year;
        }

        function returnColors(input) {
            var index;
            var returnString = "";

            for (index = 0; index < input.length; ++index) {
                //Return the correct classlist
                if (typeof input[index].class !== 'undefined') {
                    var classList = "fa fa-circle text-" + input[index].class;
                } else {
                    var classList = "fa fa-circle text-secondary";
                }

                returnString += '<span>' + input[index].color + '<i class="' + classList + '" aria-hidden="true"></i></span>';
            }

            return returnString;
        }

        function returnStatusBadge(status) {
            switch (status) {
                case 'lost':
                    return '<span class="badge badge-secondary mr-1">Registrert tapt</span>';
                case 'found':
                    return '<span class="badge badge-secondary mr-1">Registrert mistet</span>';
                case 'evicted':
                    return '<span class="badge badge-danger mr-1">Kastet</span>';
                case 'police':
                    return '<span class="badge badge-info mr-1">Sendt til politi</span>';
                case 'wait_for_police':
                    return '<span class="badge badge-danger mr-1">Venter på sending til politi</span>';
                case 'canceled':
                    return '<span class="badge badge-danger mr-1">Avsluttet</span>';
                case 'wait_for_delivery':
                    return '<span class="badge badge-success">Venter på å bli utlevert</span>';
                case 'wait_for_send':
                    return '<span class="badge badge-primary mr-1">Venter på sending</span>';
                case 'sent':
                    return '<span class="badge badge-success mr-1">Sendt</span>';
                case 'wait_for_pickup':
                    return '<span class="badge badge-primary mr-1">Venter på henting</span>';
                case 'picked_up':
                    return '<span class="badge badge-success mr-1">Hentet</span>';
            }
        }

        // Create the custom widget
        const customHits = instantsearch.connectors.connectHits(renderHits);

        // Instantiate the custom widget
        search.addWidgets([
            customHits({
                container: document.querySelector('#hits'),
            })
        ]);

        search.start();
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.4.5/themes/satellite-min.css" integrity="sha256-TehzF/2QvNKhGQrrNpoOb2Ck4iGZ1J/DI4pkd2oUsBc=" crossorigin="anonymous">
@endsection
