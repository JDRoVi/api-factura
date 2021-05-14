<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table='cliente';
    protected $fillable=['id','nombre','apellido','direccion','telefono','correo'];

    public function user(){
        return $this->belongsTo('App\Models\User','idCliente');
    }
}
