<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tier;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TierController extends Controller
{
    public function index() {
        $tiers  = Tier::paginate(10);

        return view('pages.admin.setting.tiers.index', compact('tiers'));
    }


    public function destroy($tierId) {
        try{
            $tier = Tier::find($tierId);

            if(!$tier)
                throw new Exception('Tier not found', 404);

            $tier->delete();

            Alert::success('Success', 'Tier deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally {
            return back();
        }
    }
}
