<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    public function persona(){

        // Relacion de uno a uno entre documento y Persona
        return $this->hasMany(Persona::class);
    }
}
