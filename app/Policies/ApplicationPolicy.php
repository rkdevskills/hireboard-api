<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
//use Illuminate\Auth\Access\Response;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->role === 'employer') {
            return $user->id === $application->jobListing->employer_id;
        } elseif ($user->role === 'candidate') {
            return $user->id === $application->candidate_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'candidate';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application): bool
    {
        if ($user->role === 'employer') {
            return $user->id === $application->jobListing->employer_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): bool
    {
        if ($user->role === 'candidate') {
            return $user->id === $application->candidate_id;
        }
        return false;
    }
}
