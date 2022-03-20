<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, Game, Tier};
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TierController extends Controller
{
    public function index() {
        $tiers  = Tier::orderBy('price_increase', 'asc')
            ->paginate(10);

        return view('pages.admin.setting.tiers.index', compact('tiers'));
    }


    public function show($tierId) {
        $tier       = Tier::find($tierId);
        $games      = Game::orderBy('name', 'asc')->paginate(10);
        $appSetting = AppSetting::first();

        if(!$tier) return abort(404);

        return view('pages.admin.setting.tiers.show', compact('tier', 'games', 'appSetting'));
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'name'           => 'required|max:50',
                'price_increase' => 'required|numeric|digits_between:1,6',
                'min_order'      => 'required|numeric|digits_between:1,18'
            ]);

            Tier::create([
                'name'           => $request->name,
                'price_increase' => $request->price_increase,
                'min_order'      => $request->min_order
            ]);

            Alert::success('Success', 'Tier created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally {
            return back();
        }
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


    public function update(Request $request, $tierId) {
        try{
            $request->validate([
                'name'           => 'required|max:50',
                'price_increase' => 'required|numeric|digits_between:1,6',
                'min_order'      => 'required|numeric|digits_between:1,18'
            ]);

            $tier = Tier::find($tierId);

            if(!$tier)
                throw new Exception('Tier not found', 404);

            $tier->update([
                'name'           => $request->name,
                'price_increase' => $request->price_increase,
                'min_order'      => $request->min_order
            ]);

            Alert::success('Success', 'Tier edited successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally {
            return back();
        }
    }
}
