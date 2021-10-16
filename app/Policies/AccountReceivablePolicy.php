<?php

namespace App\Policies;

use App\Models\tenant\User;
use App\Models\tenant\AccountReceivable;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountReceivablePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\tenant\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function view(User $user, AccountReceivable $accountReceivable)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function update(User $user, AccountReceivable $accountReceivable)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function delete(User $user, AccountReceivable $accountReceivable)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function restore(User $user, AccountReceivable $accountReceivable)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountReceivable  $accountReceivable
     * @return mixed
     */
    public function forceDelete(User $user, AccountReceivable $accountReceivable)
    {
        //
    }
}
