<?php

namespace App\Policies;

use App\Models\tenant\User;
use App\Models\tenant\AccountDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountDetailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function view(User $user, AccountDetail $accountDetail)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
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
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function update(User $user, AccountDetail $accountDetail)
    {
        return $accountDetail->account->status == true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function delete(User $user, AccountDetail $accountDetail)
    {
        return $accountDetail->account->status == true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function restore(User $user, AccountDetail $accountDetail)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\tenant\User  $user
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function printfRecibo(User $user, AccountDetail $accountDetail)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return mixed
     */
    public function forceDelete(User $user, AccountDetail $accountDetail)
    {
        //
    }
}
