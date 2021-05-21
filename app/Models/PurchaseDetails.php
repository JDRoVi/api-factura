<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;
    protected $table='detallecompra';
    protected $fillable = ['codigoProducto','idCompra','precioUnidad','cantidad','subtotal'];
    public function purch(){
        return $this->belongsTo('App\Models\Purchase','idCompra');
    }
}
