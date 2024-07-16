<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMateri extends Model
{
    use HasFactory;

    protected $fillable = [
        'materi_id',
        'user_id',
        'status',
    ];

    protected $guarded = [
        'id',
    ];

    protected function User()
    {
        return $this->belongsTo(User::class);
    }

    protected function Materi()
    {
        return $this->belongsTo(Materi::class);
    }
}
