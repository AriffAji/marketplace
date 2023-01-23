<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'name',
        'users_id',
        'categories_id',
        'price',
        'description',
        'slug',
    ];
    protected $hidden = []; // digunakan untuk tidak menampilkan sebuah field dalam memanggil model
    protected $table = 'products';
    public function galleries()
    {   //ini adalah untuk menyambungkan relasi(nama model, foreignkey, primary key) hasmany/hasone hanya berada pada table induk //untuk menampilkan dat yang telah terhapus dengan softdeletes
        return $this->hasMany(ProductGallery::class, 'products_id', 'id')->withTrashed();
    }
    public function user()
    {
        return $this->hasOne(user::class, 'id', 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'Categories_id', 'id');
    }
}