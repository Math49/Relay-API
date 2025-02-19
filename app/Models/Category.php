<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'ID_category';
    protected $fillable = ['Label'];

    // Une catégorie a plusieurs produits
    public function products()
    {
        return $this->hasMany(Product::class, 'ID_category', 'ID_category');
    }

    // Une catégorie a plusieurs catégories activées
    public function categoryEnables()
    {
        return $this->hasMany(CategoryEnable::class, 'ID_category', 'ID_category');
    }

}