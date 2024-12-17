<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuanLyGV extends Model
{
    use HasFactory;

    protected $table = 'QuanLyGV';

    protected $fillable = [
        'msgv',
        'malop',
    ];
}
