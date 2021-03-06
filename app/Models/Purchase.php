<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $table='compra';
    protected $fillable = ['idBodeguero','idDetalleVenta','idProveedor','fecha','total'];
    public function detail(){
        return $this->hasMany('App\Models\PurchaseDetails','idcompra');
    }
}
