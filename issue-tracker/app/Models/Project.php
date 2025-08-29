<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'deadline'];
    protected $casts = [
        'start_date' => 'date',
        'deadline'   => 'date',
    ];

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
