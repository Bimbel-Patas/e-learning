<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'tugas_id',
        'user_id',
        'status',
        'nilai',
    ];

    protected $guarded = [
        'id',
    ];

    public function Tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function UserTugasFile()
    {
        return $this->hasMany(UserTugasFile::class);
    }
}
