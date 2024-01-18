<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $table = 'service_orders';

    protected $fillable = 
    [
        'vehiclePlate', 
        'entryDateTime', 
        'exitDateTime',
        'priceType', 
        'price',
        'userId'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
