<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = ['ID_store', 'ID_product','Nmb_boxes', 'Quantity', 'Nmb_on_shelves', 'Is_empty'];

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