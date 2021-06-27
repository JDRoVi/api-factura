<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    protected $table = 'proveedor';
    protected $fillable=['id','idEmpleado','nombre','cedulaJuridica','direccion','VolumenVentas'];

    public function products(){
        return $this->hasMany('App\Models\Product','idprovedor');
    }
    public function employees(){
        return $this->hasMany('App\Models\Employee');
    }
}
