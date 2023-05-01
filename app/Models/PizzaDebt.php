<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity_pizzas',
        'photo',
        'debt_date',
        'status',
        'quantity_sodas'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
