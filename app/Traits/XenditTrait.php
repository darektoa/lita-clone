<?php

namespace App\Traits;

use Xendit\Invoice as XenditInvoice;

trait XenditTrait{
  static public function invoice($transaction) {
    $mode     = env('XENDIT_MODE');
    $ownerId  = $mode === 'production' ? env('XENDIT_OWNER_ID') : env('XENDIT_OWNER_ID_DEV');
    
    $invoice = XenditInvoice::create([
      'for-user-id'   => $ownerId,
      'external_id'   => $transaction->uuid,
      'amount'        => $transaction->balance,
      'description'   => $transaction->description ?? 'Nothing',
      'payer_email'   => $transaction->receiver->email,
      'fixed_va'      => true,
      'customer'      => [
        'given_names'   => $transaction->receiver->name,
        'email'         => $transaction->receiver->email,
      ]
    ]);

    return $invoice;
  }
}