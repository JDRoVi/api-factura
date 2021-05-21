<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;
    protected $table='venta';
    protected $fillable = ['idCajero','idCliente','idDetalleVenta','fecha','total'];
    public function Sell(){}
}
