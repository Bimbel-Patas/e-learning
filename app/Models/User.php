<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserJawabanKecermatan;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'kelas_id',
        'roles_id',
        'password',
        'gambar',
        'deskripsi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function DataSiswa()
    {
        return $this->belongsTo(DataSiswa::class);
    }

    public function Role()
    {
        return $this->belongsTo(Role::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function UserJawabanKecermatan()
    {
        return $this->hasMany(UserJawabanKecermatan::class);
    }

    public function Contact()
    {
        return $this->hasOne(Contact::class);
    }

    public function EditorAccess()
    {
        return $this->hasMany(EditorAccess::class);
    }

    public function Kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function User()
    {
        return $this->hasMany(Tugas::class);
    }

    public function UserTugas()
    {
        return $this->hasMany(UserTugas::class);
    }

    public function UserMateri()
    {
        return $this->hasMany(UserMateri::class);
    }

    public function UserJawaban()
    {
        return $this->hasMany(UserJawaban::class);
    }

    public function UserCommit()
    {
        return $this->hasMany(UserCommit::class);
    }
}
