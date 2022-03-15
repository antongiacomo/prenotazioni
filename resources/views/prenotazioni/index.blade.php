@extends('layouts.app')
@section('title','Index')

@php
    use Shahonseven\ColorHash;
    $colorhash = new ColorHash();
@endphp
@section('content')
    <div class="col-md-3 mt-2 position-fixed" style="right: 0.5em;z-index: 999">@include('flash::message')</div>
    <div class="container">
        @include('prenotazioni.totals_header')
        @foreach($nomi as $data => $elemento)
            <h2 class="text-primary">{{$data}}</h2>
            <div class="card-columns">


                @foreach($elemento as $lista)
                    @php
                        $ritirato = true;
                    @endphp

                    @foreach($lista as $prodotto)

                        @php $ritirato = $ritirato && ($prodotto->ritirato == '1') @endphp

                    @endforeach
                    <div class="card mt-1" style="@if ($ritirato) opacity:0.4; @endif" >

                        <div class="card-body">
                            <div class="card-title">
                                <h3>{{$lista[0]->nome}}</h3>

                            </div>

                            @foreach($lista as $prodotto)
                                <div class="container row d-flex align-items-baseline flex-nowrap">

                                        <div class=" circle"
                                              style="background-color: {{$colorhash->hex(   $prodotto->azienda)}}"></div>
                                    @if ($prodotto->imbustato==1)
                                        <i class="text-warning fa fa-shopping-bag"></i>
                                    @endif
                                    <span class="font-weight-bold">
                                            {{$prodotto->prodotto}}:
                                        </span>
                                    <div class="w-100">
                                        {{$prodotto->quantit}}
                                        @if ($prodotto->pezzi == 1)
                                            pezzi

                                        @else
                                            kg
                                        @endif

                                    </div>


                                    <div class="d-flex justify-content-end w-100">
                                        @if(!$ritirato)
                                            <button type="submit"
                                                    class="imbustato btn btn-link p-0 d-flex align-items-baseline"
                                                    data-id="{{$prodotto->id}}">
                                                <i class="fa fa-shopping-bag" style="vertical-align: top"></i>
                                            </button>
                                        @endif
                                        <button type="submit"
                                                class="btn btn-link delete p-0 d-flex align-items-baseline"
                                                data-id="{{$prodotto->id}}">
                                            <i class="fa fa-times-circle" style="vertical-align: top"></i>
                                        </button>
                                    </div>


                                </div>
                                @if (!$loop->last)
                                    <hr style="margin: 1em;">
                                @endif

                            @endforeach

                        </div>
                        <div class="card-footer text-muted text-right">

                            @if($ritirato)
                                <button class="btn btn-danger nonritirato" style="opacity: 1 !important;"
                                        data-id="{{$prodotto->id}}">Non Ritirato
                                </button>
                            @else
                                <button class="btn btn-success ritirato" data-id="{{$prodotto->id}}">Ritirato</button>
                            @endif
                        </div>
                    </div>

                @endforeach

            </div>
        @endforeach

    </div>

@endsection
@section('scripts')
    <script>


        $('.delete').click(function () {
            var id = $(this).data("id");

            swal({
                title: "Sei sicuro ?",
                text: "Una volta cancellato non potrai recuperare l'ordine!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: '/prenotazioni/' + id,
                            data: {_method: 'delete', _token: '{{csrf_token()}}'},
                            success: function (data) {
                                console.log(data);
                                location.reload();

                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                        swal("Poof! ordine cancellato!", {
                            icon: "success",

                        });
                    } else {
                        swal("OK l'ordine resta lì");
                    }
                });

        });
        $(".ritirato").click(function () {
            var id = $(this).data("id");
            $.ajax({
                url: 'prenotazioni/ritirato/' + id,
                type: 'PUT',
                data: {_token: '{{csrf_token()}}'},

                success: function (data) {
                    location.reload();
                }
            });


        });
        $(".nonritirato").click(function () {
            var id = $(this).data("id");
            $.ajax({
                url: 'prenotazioni/nonritirato/' + id,
                type: 'PUT',
                data: {_token: '{{csrf_token()}}'},

                success: function (data) {
                    location.reload();
                }
            });


        });
        $(".imbustato").click(function () {
            var id = $(this).data("id");
            $.ajax({
                url: 'prenotazioni/imbustato/' + id,
                type: 'PUT',
                data: {_token: '{{csrf_token()}}'},

                success: function (data) {
                    location.reload();
                }
            });


        });


        var coolDates = {!! $date_prenotazioni !!}.map(function (e) {
            e = Date.parse(e);
            return e;
        });

        $(".datepicker").flatpickr({
            inline: true,

            onChange: function (selectedDates, dateStr, instance) {
                var queryParameters = {}, queryString = location.search.substring(1),
                    re = /([^&=]+)=([^&]*)/g, m;

                while (m = re.exec(queryString)) {
                    queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
                }
                queryParameters['date'] = dateStr;
                location.search = $.param(queryParameters);

            },
            onDayCreate: function (dObj, dStr, fp, dayElem) {
                if (coolDates.indexOf(+dayElem.dateObj) !== -1) {
                    dayElem.className += " cool-date";
                }
            }


        });

        $(function () {
            document.addEventListener('click', toggleDocs, true);
            $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
            $('select#aziende').change(function () {
                if ($(this).val() == 'Altro')
                    $('#aziende').replaceWith('<input type="text" class="form-control" name="azienda" placeholder="Azienda">');
            });
            if ($('select#aziende option').length == 1) {
                $('select#aziende option').val('Altro').change();
            }
        });

        function toggleDocs(event) {
            console.log(event.target.className);
            if (event.target && event.target.className.includes('clickable-heading')) {

                var next = event.target.nextElementSibling;
                console.log(next);

                if (next.style.display == "none") {
                    next.style.display = "inline-block";

                } else {
                    next.style.display = "none";
                }
            }
        }


    </script>

@endsection
