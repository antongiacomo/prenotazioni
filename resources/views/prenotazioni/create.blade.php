<form method="post">
    <div class="card mt-1">

        <div class="card-header">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h3>
                @csrf
                <div class="row">
                    <div class="col-md-5"><select class="form-control" id="aziende" name="azienda">
                            @foreach ($persone as $nome)
                                <option value="{{$nome['azienda']}}" {{ ( old("azienda") == $nome['azienda'] ? "selected":"") }}>
                                    {{$nome['azienda']}}
                                </option>
                            @endforeach
                            <option value="Altro">Altro</option>
                        </select>


                    </div>
                    <hr class="vertical">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="nome" value="{{ old('nome')}}" placeholder="Nome"
                               id="color"/>
                    </div>

                </div>


            </h3>
        </div>
        <div class="card-body">
            <input type="hidden" name="created_at" value="{{app('request')->input('date')}}">

            <div class="row">
                <div class="col col-md-4 col-sm-12 col-12 mb-1">
                        <span class="font-weight-bold">
                            <input class="form-control" type="text" name="prodotto" value="{{ old('prodotto')}}"
                                   placeholder="Prodotto">
                        </span>
                </div>
                <div class="col col-md-4 col-sm-12  col-12 mb-1">

                    <input type="text" class="form-control" placeholder="Quantità" value="{{ old('quantit')}}"
                           name="quantit">


                </div>
                <div class="col col-md-4 col-sm-12 col-12 mb-1">
                    <input type="checkbox" name="unit" data-toggle="toggle" data-on="Kg"
                           data-off="Pezzi" data-onstyle="success" data-offstyle="primary">
                </div>
            </div>


        </div>
        <div class="card-footer text-muted text-right">

            <div>
                <button type="submit" class="btn btn-success">Inserisci</button>
            </div>
        </div>
    </div>
</form>

