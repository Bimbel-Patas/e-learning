<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCommit extends Model
{
    protected $fillable = [
        'user_id',
        'ujian_id',
        'start_time',
        'end_time',
        'due',
        'status',
    ];

    protected $guarded = [
        'id',
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
