<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $table = 'addresses';
    protected $fillable = [
        'cep',
        'street',
        'neighborhood',
        'city',
        'state'
    ];

    public  function users(){
        return $this->hasOne(User::class);
    }

    public function companies (){
        return $this->hasOne(Company::class);
    }
}
