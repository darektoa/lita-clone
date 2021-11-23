<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FAQController extends Controller
{
    public function index() {
        $FAQs = FAQ::paginate(10);

        return view('pages.admin.setting.faqs.index', compact('FAQs'));
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'question'  => 'required',
                'answer'    => 'required',
            ]);

            FAQ::create([
                'question'  => $request->question,
                'answer'    => $request->answer
            ]);

            Alert::success('Success', 'FAQ created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }


    public function update(Request $request, $faqId) {
        try{
            $request->validate([
                'question'  => 'required',
                'answer'    => 'required',
            ]);

            $faq = FAQ::find($faqId);

            if(!$faq)
                throw new Exception('FAQ not found', 404);

            $faq->update([
                'question'  => $request->question,
                'answer'    => $request->answer
            ]);

            Alert::success('Success', 'FAQ updated successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally {
            return back();
        }
    }


    public function destroy($faqId) {
        try{
            $faq = FAQ::find($faqId);

            if(!$faq)
                throw new Exception('FAQ not found', 404);

            $faq->delete();

            Alert::success('Success', 'FAQ deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Success', $errMessage);
        }finally{
            return back();
        }
    }
}
