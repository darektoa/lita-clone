<?php

namespace Database\Seeders;

use App\Models\TransferAvailable;
use Illuminate\Database\Seeder;

class TransferAvailableSeeder extends Seeder
{
    public function run()
    {
        $banks      = ['BCA', 'BNI', 'BRI'];
        $eWallets   = ['DANA', 'GOPAY', 'OVO', 'SHOPEEPAY'];
        
        foreach($banks as $bank)
            TransferAvailable::create(['name'=>$bank, 'type'=>0]);
        
        foreach($eWallets as $eWallet)
            TransferAvailable::create(['name'=>$eWallet, 'type'=>1]);
    }
}
