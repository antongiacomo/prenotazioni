<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prenotazioni extends Model
{

    protected $fillable = ['nome', 'prodotto', 'quantit'];
    protected $table = 'prenotazioni';
}
