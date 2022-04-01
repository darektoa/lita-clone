<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::orderBy('name', 'asc')
            ->paginate(10);

        return view('pages.admin.setting.services.index', compact('services'));
    }
}
