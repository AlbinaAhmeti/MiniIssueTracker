<?php

namespace App\Policies;

use App\Models\Issue;
use App\Models\User;
use App\Models\Project;

class IssuePolicy
{
    public function update(User $user, Issue $issue): bool
    {
        return $issue->created_by === $user->id
            || $issue->project?->owner_id === $user->id;
    }

    public function delete(User $user, Issue $issue): bool
    {
        return $issue->created_by === $user->id
            || $issue->project?->owner_id === $user->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $project->owner_id === $user->id;
    }

        public function manageTags(User $user, Issue $issue): bool
    {
        return (int) $issue->created_by === (int) $user->id;
    }

    public function manageMembers(User $user, Issue $issue): bool
    {
        return (int) $issue->created_by === (int) $user->id;
    }
}
