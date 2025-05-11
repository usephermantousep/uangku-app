<?php

namespace App\Policies;

use App\Models\LaterTransaction;
use App\Models\User;
use App\Util\PermissionUtil;
use Illuminate\Auth\Access\Response;

class LaterTransactionPolicy
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
    public function view(User $user, LaterTransaction $laterTransaction): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LaterTransaction $laterTransaction): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LaterTransaction $laterTransaction): bool
    {
        return $laterTransaction->createdBy->id == $user->id || $user->hasAnyRole(PermissionUtil::$adminAndHoF);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LaterTransaction $laterTransaction): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LaterTransaction $laterTransaction): bool
    {
        return true;
    }
}
