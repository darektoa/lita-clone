<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Xendit\Invoice as XenditInvoice;
use Xendit\Platform as XenditPlatform;

trait XenditTrait{
  static public function invoice($transaction) {
    $mode     = env('XENDIT_MODE');
    $ownerId  = $mode === 'production' ? env('XENDIT_OWNER_ID') : env('XENDIT_OWNER_ID_DEV');
    
    $invoice = XenditInvoice::create([
      'for-user-id'     => $ownerId,
      'external_id'     => $transaction->uuid,
      'amount'          => $transaction->balance,
      'description'     => $transaction->description ?? 'Nothing',
      'payer_email'     => $transaction->receiver->email,
      'fixed_va'        => true,
      'customer'        => [
        'given_names'     => $transaction->receiver->name,
        'email'           => $transaction->receiver->email,
      ],
      'payment_methods' => [
        'CREDIT_CARD', 'BNI', 'BRI', 'MANDIRI', 'PERMATA',
        'ALFAMART', 'OVO', 'LINKAJA',
      ]
    ]);

    return $invoice;
  }


  static public function transfer($amount, $destination_user_id) {
    $createTransfer = XenditPlatform::createTransfer([
      'reference'           => Str::uuid(),
      'amount'              => $amount,
      'source_user_id'      => '54afeb170a2b18519b1b8768',
      'destination_user_id' => $destination_user_id,
    ]);

    return $createTransfer;
  }
}