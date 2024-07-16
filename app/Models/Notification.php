<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'body',
        'excerpt',
    ];

    protected $guarded = [
        'id',
    ];

    public function Notification()
    {
        return $this->belongsTo(User::class);
    }
}
