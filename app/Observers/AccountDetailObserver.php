<?php

namespace App\Observers;

use App\Models\tenant\AccountDetail;
use App\Models\tenant\AccountReceivable;
use Exception;

class AccountDetailObserver
{
    /**
     * Handle the AccountDetail "created" event.
     *
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return void
     */
    public function saving(AccountDetail $accountDetail)
    {  
        $saldo = ($accountDetail->account->quantity - $accountDetail->account->details->sum('amount'));
        if ($accountDetail->amount > $saldo) {
            throw_if(
                Exception::class,
                'El monto sobrepasa al saldo: '.$saldo
            );
        }
        if ($accountDetail->amount == $saldo) {
            $accountDetail->account->update(['status' => false]);
        }
    }

    /**
     * Handle the AccountDetail "updated" event.
     *
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return void
     */
    public function updated(AccountDetail $accountDetail)
    {
        //
    }

    /**
     * Handle the AccountDetail "deleted" event.
     *
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return void
     */
    public function deleted(AccountDetail $accountDetail)
    {
        //
    }

    /**
     * Handle the AccountDetail "restored" event.
     *
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return void
     */
    public function restored(AccountDetail $accountDetail)
    {
        //
    }

    /**
     * Handle the AccountDetail "force deleted" event.
     *
     * @param  \App\Models\tenant\AccountDetail  $accountDetail
     * @return void
     */
    public function forceDeleted(AccountDetail $accountDetail)
    {
        //
    }
}
