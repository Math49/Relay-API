<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'ID_message';
    protected $fillable = ['Message', 'Creation_date', 'Deletion_date', 'ID_store'];

    public function store()
    {
        return $this->belongsTo(Store::class, 'ID_store', 'ID_store');
    }
}