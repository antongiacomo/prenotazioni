<?php

namespace App\Http\Controllers;

use App\Prenotazioni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PrenotazioniController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $date = Input::get('date');

        $date_prenotazioni = json_encode(
            array_keys(
                Prenotazioni::select('created_at')
                    ->distinct('created_at')
                    ->whereDate('created_at', '>=', Carbon::today())
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->created_at)->format('m-d-Y');
                    })
                    ->toArray()
            )
        );

        $nomi = Prenotazioni::orderBy('created_at', 'desc')
            ->orderBy('ritirato', 'asc')
            ->orderBy('azienda', 'asc');

        if (isset($date)) {
            $nomi = $nomi
                ->whereDate('created_at', '=', Carbon::parse($date))
                ->get();
        } else {
            $nomi = $nomi
                ->get();
        }

        $nomi = $nomi->groupBy([
            function ($val) {
                return Carbon::parse($val->created_at)->format('d M Y');
            },
            'nome',
        ]);

        $totali = Prenotazioni::orderBy('prodotto')->orderBy('pezzi')->orderBy('quantit', 'desc');

        if (isset($date)) {
            $totali = $totali->whereDate('created_at', '=', Carbon::parse($date));
        }

        $totali = $totali
            ->get()
            ->groupBy(['azienda', 'prodotto', 'pezzi', 'quantit']);

        $persone = Prenotazioni::distinct()->select('azienda')->get();

        return view('prenotazioni.index', compact('nomi', 'totali', 'persone', 'date_prenotazioni'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nome' => 'required',
            'quantit' => 'required|numeric',
            'prodotto' => 'required',
            'azienda' => 'required',
        ]);

        $prenotazione = new Prenotazioni();
        $prenotazione->prodotto = trim(ucfirst(strtolower($request->prodotto)));
        $prenotazione->nome = trim(ucwords(strtolower($request->nome)));
        $prenotazione->azienda = $request->azienda;
        $prenotazione->quantit = $request->quantit;
        $prenotazione->created_at = Carbon::parse($request->created_at);
        if ($request->get('unit') != "on") {
            $prenotazione->pezzi = '1';
        }

        if ($prenotazione->save()) {
            flash('Ordine Inserito!')->success();
        } else {
            flash("Errore inserimento")->error();
        }

        return redirect()
            ->route('prenotazioni.index', ['date' => Input::get('date')])
            ->withInput(Input::except('prodotto', 'quantit'));
    }

    public function ritirato($id)
    {
        $prenotazione = Prenotazioni::find($id);

        Prenotazioni::
        whereDay('created_at', '=', $prenotazione->created_at
            ->format('d'))
            ->where('nome', '=', $prenotazione->nome)
            ->update(['ritirato' => 1]);
    }

    public function notRitirato($id)
    {
        $prenotazione = Prenotazioni::find($id);

        Prenotazioni::
        whereDay('created_at', '=', $prenotazione->created_at
            ->format('d'))
            ->where('nome', '=', $prenotazione->nome)
            ->update(['ritirato' => 0]);
    }

    public function imbustato($id)
    {
        $prenotazione = Prenotazioni::find($id);

        $prenotazione->imbustato = ! $prenotazione->imbustato;
        $prenotazione->save();
    }

    public function destroy($id)
    {

        Prenotazioni::destroy($id);
        flash('Eliminato con successo.')->success();
        return redirect()
            ->route('prenotazioni.index', ['date' => Input::get('date')]);
    }
}
