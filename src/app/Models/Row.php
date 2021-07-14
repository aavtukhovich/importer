<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    protected $table = 'rows';

    protected $fillable = ["id", "name", "date"];

    protected $dateFormat = 'd.m.Y';
}
