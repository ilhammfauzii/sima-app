<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterData extends Model
{
    use HasFactory;
    
    protected $table = 'master_data';

    protected $fillable = [
        'master_id',
        'data_master',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class, 'master_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'departemen_id');
    }

}