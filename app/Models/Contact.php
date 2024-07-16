<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_telp',
        'user_id',
        'nama_wali',
        'no_telp_wali',
    ];

    protected $guarded = [
        'id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
