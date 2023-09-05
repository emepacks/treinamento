<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    public $table = 'companies';

    protected $fillable = [
        'name',
        'cnpj',
        'address_id',
        'user_id'
    ];

    public function address ()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function user (){
        return $this->hasMany(User::class, 'user_id');
    }
}
