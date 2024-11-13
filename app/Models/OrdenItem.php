<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'orden_id',
        'producto_id',
        'cantidad',
        'precio'
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class);
    }

    public function product()
    {
        return $this->belongsTo(Producto::class);
    }
}
