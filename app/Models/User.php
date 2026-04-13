<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'nomor_pegawai',
        'email',
        'password',
        'id_roles',
        'jabatan',
        'departemen_id',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_roles');
    }

    public function departemen()
    {
        return $this->belongsTo(MasterData::class, 'departemen_id');
    }

    public function isSuperAdmin()
    {
        return $this->role && $this->role->nama_roles === 'Super Admin';
    }

    public function isAdmin()
    {
        return $this->role && $this->role->nama_roles === 'Admin';
    }

    public function isUser()
    {
        return $this->role && $this->role->nama_roles === 'User';
    }

}