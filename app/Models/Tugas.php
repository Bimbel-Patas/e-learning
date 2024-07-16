<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_mapel_id',
        'name',
        'content',
        'due',
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

    public function UserTugas()
    {
        return $this->hasmany(UserTugas::class);
    }

    public function TugasFile()
    {
        return $this->hasMany(TugasFile::class);
    }
}
