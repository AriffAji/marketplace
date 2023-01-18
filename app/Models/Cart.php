<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'products_id',
        'user_id',

    ];
    protected $hidden = []; // digunakan untuk tidak menampilkan sebuah field dalam memanggil model
    protected $table = 'carts';

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'users_id');
    }
}