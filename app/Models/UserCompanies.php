<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCompanies extends Pivot
{
    use HasFactory, HasFactory;
    protected $table = 'company_user';
}
