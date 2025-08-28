<?php

namespace App\Models;

use com_exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /** @use HasFactory<\Database\Factories\IssueFactory> */
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date'];

    protected $casts = ['due_date' => 'date'];

    public const STATUS = ['open', 'in_progress', 'closed'];
    public const PRIORITY = ['low', 'medium', 'high'];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class)->latest();
    }

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

}
