<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'gambar',
        'name',
        'deskripsi',
    ];

    protected $guarded = [
        'id',
    ];

    public function KelasMapel()
    {
        return $this->hasMany(KelasMapel::class);
    }
}
