<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ListModel extends Model
{
    use HasFactory;

    protected $table = 'lists';
    protected $primaryKey = 'ID_list';
    protected $fillable = ['ID_store', 'Creation_date'];

    // Une liste appartient à un magasin
    public function store()
    {
        return $this->belongsTo(Store::class, 'ID_store', 'ID_store');
    }

    // Une liste contient des produits
    public function productLists(){
        return $this->hasMany(ProductList::class, 'ID_list', 'ID_list');
    }
}