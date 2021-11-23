<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index() {
        $FAQs = FAQ::paginate(10);

        return view('pages.admin.setting.faqs.index', compact('FAQs'));
    }
}
