<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = ['ID_store', 'ID_product', 'Quantity', 'Nmb_on_shelves', 'Is_empty'];

    // Un stock appartient à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'ID_store', 'ID_store');
    }

    // Un stock appartient à un produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'ID_product', 'ID_product');
    }
}