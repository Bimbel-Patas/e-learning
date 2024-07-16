<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'user_id',
        'status',
        'nilai',
    ];

    public function Ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
