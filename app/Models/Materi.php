<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_mapel_id',
        'name',
        'content',
        'isHidden',
    ];

    protected $guarded = [
        'id',
    ];

    public function KelasMapel()
    {
        return $this->belongsTo(KelasMapel::class);
    }

    public function ForumDiskusi()
    {
        return $this->hasMany(ForumDiskusi::class);
    }

    public function MateriFile()
    {
        return $this->hasMany(MateriFile::class);
    }

    public function UserMateri()
    {
        return $this->hasMany(UserMateri::class);
    }
}
