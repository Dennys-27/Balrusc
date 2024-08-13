<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    use HasFactory;

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }


    //RELACION DE UNO A MUCHOS EN PLIRAL POR QUE UN PROVEEDOR PUEDE HACER MUCHAS COMPRAS
    public function compras()
    {
        //hasMany de uno a muchos
        return $this->hasMany(Compra::class);
    }

    protected $fillable = ['persona_id'];
}
