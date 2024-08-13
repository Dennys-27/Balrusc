<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    use HasFactory;

    //RELACION DE UNO A MUCHOS EN PLIRAL POR QUE UN PROVEEDOR PUEDE HACER MUCHAS COMPRAS
    public function compras()
    {
        //hasMany de uno a muchos
        return $this->hasMany(Compra::class);
    }


    public function ventas()
    {
        //hasMany de uno a muchos
        return $this->hasMany(Venta::class);
    }
}
