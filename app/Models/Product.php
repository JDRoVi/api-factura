<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $fillable = ['CodigoProducto','idprovedor','nombre','cantidad','fechaCaducidad','precioUnidad'];

    public function Provider(){
        return $this->belongsTo('App\Models\Provider','idprovedor');
    }
}
