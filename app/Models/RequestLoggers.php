<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLoggers extends Model
{
    use HasFactory;
    protected $table = 'resquest_loggers';
    protected $fillable = [
        'method',
        'url',
        'ip',
        'request',
    ];
}
