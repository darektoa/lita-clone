<?php

namespace App\Http\Controllers\Web;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::orderBy('name', 'asc')
            ->paginate(10);

        return view('pages.admin.setting.services.index', compact('services'));
    }


    public function update(Request $request, $serviceId) {
        $service = Service::find($serviceId);

        try{
            $this->validate($request, [
                'name'           => 'required|max:255',
                'icon'           => 'nullable|image|max:2048',
                'price'          => 'required|numeric|digits_between:0,18',
                'player_revenue' => 'required|numeric|min:0',
            ]);

            if(!$service) throw new Exception('service not found');
            
            $service->name           = $request->name;
            $service->price          = $request->price;
            $service->player_revenue = $request->player_revenue;

            if($request->icon) {
                $iconPath = StorageHelper::put('images/service-icons', $request->icon);
                StorageHelper::delete($service->icon);
                $service->icon = $iconPath;
            }

            $service->update();
            Alert::success('Success', 'Service edited successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
