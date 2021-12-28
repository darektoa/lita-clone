<?php

namespace Database\Seeders;

use App\Models\AvailableTransfer;
use Illuminate\Database\Seeder;

class AvailableTransferSeeder extends Seeder
{
    public function run()
    {
        $banks      = ['BCA', 'BNI', 'BRI'];
        $eWallets   = ['DANA', 'GOPAY', 'OVO', 'SHOPEEPAY'];
        
        foreach($banks as $bank)
            AvailableTransfer::create(['name'=>$bank, 'type'=>0]);
        
        foreach($eWallets as $eWallet)
            AvailableTransfer::create(['name'=>$eWallet, 'type'=>1]);
    }
}
