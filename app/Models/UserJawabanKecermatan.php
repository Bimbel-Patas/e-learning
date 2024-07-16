<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kecermatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserJawabanKecermatan extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "user_id",
        "kecermatan_id",
        "soal",
        "jawaban",
        "jawaban_user",
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Kecermatan()
    {
        return $this->belongsTo(Kecermatan::class);
    }
}
