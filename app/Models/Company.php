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
    ];

    public function address ()
    {
        return $this->hasOne(Address::class, 'address_id');
    }

    public function user (){
        return $this->hasMany(User::class, 'user_id');
    }
}
