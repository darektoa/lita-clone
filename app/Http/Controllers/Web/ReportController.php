<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $reports = Report::latest()->paginate(10);

        return view('pages.admin.reports.index', compact('reports'));
    }
}
