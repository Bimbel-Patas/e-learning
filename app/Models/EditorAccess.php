<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_mapel_id',
    ];

    protected $guarded = [
        'id',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function KelasMapel()
    {
        return $this->belongsTo(KelasMapel::class);
    }
}
