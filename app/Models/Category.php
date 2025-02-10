<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'ID_category';
    protected $fillable = ['Label'];

    // Une catégorie a plusieurs produits
    public function products()
    {
        return $this->hasMany(Product::class, 'ID_category', 'ID_category');
    }

}