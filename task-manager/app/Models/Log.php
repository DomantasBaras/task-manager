<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    // Define the fields you want to allow mass assignment for
    protected $fillable = [
        'entity_type',
        'entity_id',
        'field_changed',
        'old_value',
        'new_value',
    ];

    // If you want to automatically timestamp each log entry
    public $timestamps = true;
}
