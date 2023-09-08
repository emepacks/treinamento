<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'addresses';
    protected $fillable = [
        'cep',
        'street',
        'neighborhood',
        'city',
        'state'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
    public function company(){
        return $this->hasOne(Company::class);
    }

}
