<?php

namespace App\Policies;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClinicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_owner | $user->is_doctor || $user->is_patient;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Clinic $clinic): bool
    {
        return $user->clinics->contains($clinic->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_owner;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Clinic $clinic): bool
    {
        return $user->clinics->contains($clinic->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Clinic $clinic): bool
    {
        return $user->clinics->contains($clinic->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Clinic $clinic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Clinic $clinic): bool
    {
        return false;
    }
}
