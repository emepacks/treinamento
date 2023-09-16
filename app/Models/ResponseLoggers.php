<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseLoggers extends Model
{
    use HasFactory;
    protected $table = 'response_loggers';
    protected $fillable = [
        'method',
        'age',
        'statusCode',
        'response',
    ];
}
