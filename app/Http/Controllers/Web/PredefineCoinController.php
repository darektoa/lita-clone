<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PredefineCoin;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PredefineCoinController extends Controller
{
    public function index() {
        $coins = PredefineCoin::paginate(10);

        return view('pages.admin.setting.coins.index', compact('coins'));
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'coin'      => 'bail|required|numeric|digits_between:0,18',
                'balance'   => 'required|numeric|digits_between:0,18'
            ]);

            PredefineCoin::create([
                'coin'      => $request->coin,
                'balance'   => $request->balance
            ]);
            Alert::success('Success', 'Coin created successfully');
        }catch(Exception $err){
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
           return back(); 
        }
    }


    public function destroy($coinId) {
        $coin = PredefineCoin::find($coinId);

        try{
            if(!$coin) throw new Exception('Coin not found');

            $coin->delete();
            Alert::success('Success', 'Coin deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
