<?php

namespace App\Observers;

use App\Models\ModeOfPayment;

class ModeOfPaymentObserver
{
    /**
     * Handle the ModeOfPayment "created" event.
     */
    public function created(ModeOfPayment $modeOfPayment): void
    {
        if (auth()->user()) {
            $modeOfPayment->family()->associate(auth()->user()->family);
        }
    }
}
