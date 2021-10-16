<?php

namespace App\Observers;

use App\Models\tenant\Transfer;
use App\Models\tenant\Transacction;

class TransferObserver
{
    /**
     * Handle the Transfer "created" event.
     *
     * @param  \App\Models\tenant\Transfer  $transfer
     * @return void
     */
    public function creating(Transfer $transfer)
    {
        $account1 = Account::findOrFail($request->expense_account_id);
        $account2 = Account::findOrFail($request->income_account_id);

        $transacction = Transacction::create(
            [
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'income',
                'paid_at' => $request->fecha,
                'currency_code' => $account2->currency->code,
                'currency_rate' => $account2->currency->rate,
                'amount' => $request->amount,
                'contact_id' => 0,
                'description' => $request->descripcion,
                'category_id' => 1,
                'user_id' => auth()->user()->id
            ]
        );
        $transacction1 = Transacction::create(
            [
                'sucursal_id' => session()->get('sucursal'),
                'type' => 'expense',
                'paid_at' => $request->fecha,
                'currency_code' => $account1->currency->code,
                'currency_rate' => $account1->currency->rate,
                'amount' => $request->amount,
                'contact_id' => 0,
                'description' => $request->descripcion,
                'category_id' => 1,
                'user_id' => auth()->user()->id
            ]
        );
        $transfer->expense_transaction_id = $transacction1;
        $transfer->income_transaction_id = $transacction;
        $transfer->sucursal_id = session()->get('sucursal');

    }

    /**
     * Handle the Transfer "updated" event.
     *
     * @param  \App\Models\tenant\Transfer  $transfer
     * @return void
     */
    public function updated(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "deleted" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function deleted(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "restored" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function restored(Transfer $transfer)
    {
        //
    }

    /**
     * Handle the Transfer "force deleted" event.
     *
     * @param  \App\Models\Transfer  $transfer
     * @return void
     */
    public function forceDeleted(Transfer $transfer)
    {
        //
    }
}
