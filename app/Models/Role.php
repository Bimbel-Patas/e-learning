<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $guarded = [
        'id',
    ];

    public function User()
    {
        return $this->hasMany(User::class);
    }
}
