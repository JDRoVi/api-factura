<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $fillable = ['idP','nombre','cantidad','fechaCaducidad','precioUnidad'];
}
