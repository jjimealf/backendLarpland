<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',
        'valoracion_total',
        'imagen',
        'categoria',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'valoracion_total' => 'decimal:2',
        ];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Product_Review::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(Detail_Order::class);
    }
}
