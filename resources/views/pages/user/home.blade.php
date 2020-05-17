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
    <script src="{{ asset('js/twbspagination.min.js') }}"></script>
    <script>
        function markProcessed(id, reference) {
                axios.post('case/' + reference + '/readconversation', {
                    message: id,
                })
                    .then(function (response) {
                        if(response.status) {
                            document.getElementById("messageProcess-" + id).disabled = true;
                        } else {
                            alert('Kunne ikke merke som lest');
                        }
                    })
                    .catch(function (error) {
                        alert('Kunne ikke merke som lest: ' + error);
                        console.log(error);
                    });
        }



        var callable = true;

        function doSearch(lengthcheck=true, page=1) {
            var query = document.getElementById("searchQuery").value;

            if ((query.length > 4 && callable) || lengthcheck == false) {
                // Optionally the request above could also be done as
                axios.get('{{ route('item_search') }}', {
                    params: {
                        query: query
                    }
                }).then(function (response) {
                    console.log(response);
                    var pageCount = response.data.total;

                    //Disable the function call for a second
                    var callable = false;
                    setTimeout(function(){
                        var callable = true;
                    }, 1500);

                    console.log(response.data);
                    if (response.data != "[]") {
                        //Request the html content
                        //console.log(response.data);
                        var htmlContent = axios({
                            method: 'post',
                            url: '{{ route('show_results_search') }}',
                            data: {
                                data: response.data.data
                            }
                        }).then(function (content) {
                            console.log(content)
                            document.getElementById("search-results").innerHTML = content.data;


                            //Do the pagination
                            $('#search-pagination').twbsPagination({
                                totalPages: pageCount,
                                visiblePages: 5,
                                first: 'Første',
                                prev: 'Forrige',
                                next: 'Neste',
                                last: 'Siste',
                                paginationClass: 'pagination mx-auto justify-content-center',
                                onPageClick: function (event, page) {
                                    doSearch(page);
                                }
                            });

                        }).catch(function (error) {
                            document.getElementById("search-results").innerHTML = '<div class="text-center">Det gikk noe galt i det søket skulle gjøres</div>';
                        });
                    } else {
                        document.getElementById("search-results").innerHTML = '<div class="text-center">Ingen resultater med det søkeordet</div>';
                    }
                }).catch(function (error) {
                    document.getElementById("search-results").innerHTML = '<div class="text-center">Det gikk noe galt i det søket skulle gjøres</div>';
                })
            }
        }
    </script>
@endsection
