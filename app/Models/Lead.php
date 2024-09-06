<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_AT_WORK = 'at_work';
    public const STATUS_COMPLETED = 'completed';
}
