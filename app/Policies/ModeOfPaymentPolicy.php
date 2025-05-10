<?php

namespace App\Policies;

use App\Models\ModeOfPayment;
use App\Models\User;
use App\Util\PermissionUtil;
use Illuminate\Auth\Access\Response;

class ModeOfPaymentPolicy
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
    public function view(User $user, ModeOfPayment $modeOfPayment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(PermissionUtil::$adminAndHoF);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ModeOfPayment $modeOfPayment): bool
    {
        return $user->hasAnyRole(PermissionUtil::$adminAndHoF);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeOfPayment $modeOfPayment): bool
    {
        return $user->hasAnyRole(PermissionUtil::$adminAndHoF);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeOfPayment $modeOfPayment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeOfPayment $modeOfPayment): bool
    {
        return true;
    }
}
