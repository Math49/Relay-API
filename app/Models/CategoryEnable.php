<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryEnable extends Model
{

    use HasFactory;

    protected $table = 'categories_enable';

    protected $primaryKey = 'ID_category_enable';
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