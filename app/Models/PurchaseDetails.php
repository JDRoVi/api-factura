<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    use HasFactory;
    protected $table='detallecompra';
    protected $fillable = ['codigoProducto','idcompra','precioUnidad','cantidad','subtotal'];
}
