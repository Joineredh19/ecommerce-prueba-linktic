<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orden extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'orden_numero',
        'monto_total',
        'estado'
    ];

    public function items()
    {
        return $this->hasMany(OrdenItem::class);
    }
}
