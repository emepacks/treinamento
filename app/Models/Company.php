<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    public $table = 'companies';

    protected $fillable = [
        'name',
        'cnpj',
        'email',
        'password',
        'address_id',
    ];

    public function user(){
        return $this->belongsToMany(User::class)->using(UserCompanies::class);
    }

    public function address(){
        return $this->hasOne(Address::class, 'address_id', 'id');
    }
}
