<div class="row mb-4">
    <div class="col-md-4" style="min-width: 377px;">
        <div class="card mt-2 mb-2">
            <div class="card-body">
                <div class="card-title">
                    <p class="display-4">Data</p>
                    <p>
                        Oggi:<strong> {{ucfirst(\Carbon\Carbon::today()->locale('it_IT')->isoFormat('dddd, LL'))}}</strong>
                    </p>
                </div>
                <div class="datepicker"></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mt-2 mb-2">

            <div class="card-body">
                <div class="card-title">
                    <p class="display-4">Ordini totali</p>
                </div>
                <div class="row">

                    @foreach($totali as $azienda => $lista)
                        <div class="col">
                            <p class="h5 text-primary"> {{$azienda}}</p>
                            @foreach($lista as $nome => $prodotto)
                                <span class=" clickable-heading" style="font-weight: 500;"> {{$nome}}: </span>
                                <span class="text-success " style="display: none">
                                @foreach($prodotto as $unit => $pezzi)

                                        @php

                                            $total=0;

                                        @endphp
                                        @foreach($pezzi as  $dimensione_busta => $pezzo)
                                            @php
                                                $to_print=0;
                                            @endphp
                                            @foreach($pezzo as  $busta)

                                                @php
                                                    $total+=$busta->quantit;

                                                $to_print++;



                                                @endphp
                                            @endforeach

                                            {{ $to_print}}X{{$dimensione_busta }}
                                            @if($unit == 0)
                                                kg
                                            @else
                                                pezzi
                                            @endif
                                            @if(!$loop->last)
                                                ,
                                            @else
                                                =
                                            @endif

                                        @endforeach
                                     </span>
                                <span class="font-weight-bold " style="text-decoration: underline;">
                                        {{$total}}
                                    @if($unit == 0)
                                        kg
                                    @else
                                        pezzi
                                    @endif

                                    </span>

                                <br>
                            @endforeach
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @include('prenotazioni.create')
    </div>
</div>