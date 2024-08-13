<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    //RELACION DE UNO A MUCHOS EN PLIRAL POR QUE UN PROVEEDOR PUEDE HACER MUCHAS COMPRAS
    public function ventas()
    {
        //hasMany de uno a muchos
        return $this->hasMany(Venta::class);
    }

    protected $fillable = ['persona_id'];
}
