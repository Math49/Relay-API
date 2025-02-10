<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ID_product';
    protected $fillable = ['Label', 'Box_quantity', 'Image', 'Packing', 'Barcode', 'ID_category'];

    // Un produit appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'ID_category', 'ID_category');
    }

    // Un produit peut être dans plusieurs stocks
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'ID_product', 'ID_product');
    }

    // Un produit peut être dans plusieurs listes
    public function lists()
    {
        return $this->belongsToMany(ListModel::class, 'products__lists', 'ID_product', 'ID_list')
                    ->withPivot('Quantity');
    }
}