<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellDetails extends Model
{
    use HasFactory;
    protected $table='detalleventa';
    protected $fillable = [];
    public function SellDetails(){}
}
