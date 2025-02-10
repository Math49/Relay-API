<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryEnable extends Model
{
    protected $table = 'categories_enable';
    protected $fillable = ['ID_store', 'ID_category', 'Category_position'];

    // Une activation de catégorie appartient à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'ID_store', 'ID_store');
    }

    // Une activation de catégorie appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'ID_category', 'ID_category');
    }
}