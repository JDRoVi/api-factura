<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table='empleado';
    protected $fillable=['id','nombre','apellido','direccion','telefono','correo','tipo'];

    /*public function user(){
        return $this->belongsTo('App\Models\User','idEmpleado');
    }*/
    public function company(){
        return $this->belongsTo('App\Models\Provider','idEmpleado');
    }
    
}
