<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    protected $table = 'lists';
    protected $primaryKey = 'ID_list';
    protected $fillable = ['ID_store', 'Creation_date'];

    // Une liste appartient à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'ID_store', 'ID_store');
    }

    // Une liste contient plusieurs produits
    public function products()
    {
        return $this->belongsToMany(Product::class, 'products__lists', 'ID_list', 'ID_product')
                    ->withPivot('Quantity');
    }
}