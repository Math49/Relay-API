<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'ID_store';
    protected $fillable = ['Address', 'Phone', 'Manager_name', 'Manager_phone'];

    // Un magasin a plusieurs stocks
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'ID_store', 'ID_store');
    }

    // Un magasin a plusieurs messages
    public function messages()
    {
        return $this->hasMany(Message::class, 'ID_store', 'ID_store');
    }

    // Un magasin a plusieurs listes
    public function lists()
    {
        return $this->hasMany(ListModel::class, 'ID_store', 'ID_store');
    }

    // Un magasin peut activer plusieurs catégories
    public function categoriesEnabled()
    {
        return $this->hasMany(CategoryEnable::class, 'ID_store', 'ID_store');
    }

    //un magasin peut avoir plusieurs comptes
    public function users()
    {
        return $this->hasMany(User::class, 'ID_store', 'ID_store');
    }
    
}