<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const NOT_STARTED = 0;
    const PENDING = 1;
    const COMPLETED = 2;

    protected $guarded = [];

}
