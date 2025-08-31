<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'start_date', 'deadline','owner_id', ];
    protected $casts = [
        'start_date' => 'date',
        'deadline'   => 'date',
    ];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
