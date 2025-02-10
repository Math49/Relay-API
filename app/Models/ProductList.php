<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductList extends Model
{
    protected $table = 'products__lists';
    protected $fillable = ['ID_product', 'ID_list', 'Quantity'];
    
    // Un lien appartient à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'ID_product', 'ID_product');
    }

    // Un lien appartient à une liste
    public function list()
    {
        return $this->belongsTo(ListModel::class, 'ID_list', 'ID_list');
    }
}