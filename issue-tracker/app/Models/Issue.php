<?php

namespace App\Models;
use App\Enums\IssueStatus;
use App\Enums\IssuePriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;


use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
     use HasFactory;
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];

    protected $casts = [
        'status'   => IssueStatus::class,
        'priority' => IssuePriority::class,
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeFilter($q, array $filters)
    {
        $q->when($filters['status'] ?? null, fn($qq, $v) => $qq->where('status', $v));
        $q->when($filters['priority'] ?? null, fn($qq, $v) => $qq->where('priority', $v));
        $q->when($filters['tag'] ?? null, fn($qq, $v) => $qq->whereHas('tags', fn($t) => $t->where('name', $v)));
        $q->when($filters['due_before'] ?? null, fn($qq, $v) => $qq->whereDate('due_date', '<=', $v));
        $q->when(($filters['overdue'] ?? null) === '1', fn($qq) => $qq->whereDate('due_date', '<', now())->where('status', '!=', 'closed'));
        return $q;
    }
}
