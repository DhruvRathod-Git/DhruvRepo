<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Products extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'products';
    protected $fillable = [
        'product_name',     
        'description', 
        'price', 
        'quantity',
        'stock',
    ];
    
    public function purchases()
{
    return $this->hasMany(Purchase::class);
}
}
