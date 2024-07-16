<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mapel_id',
    ];

    protected $guarded = [
        'id',
    ];

    public function EditorAccess()
    {
        return $this->hasMany(EditorAccess::class);
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function Mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function Materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function Tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function Ujian()
    {
        return $this->hasMany(Ujian::class);
    }
}
