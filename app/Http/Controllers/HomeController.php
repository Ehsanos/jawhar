<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Review;
use App\Models\Contact;
use App\Models\Page;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('website.index');
    }
    public function privacy()
    {
                $page = Page::where('id',2)->first();

        return view('website.privacy_policy',['page' => $page]);
    }
   
     public function sendContact(Request $request){
         
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'message' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $contact =new Contact();
        $contact->name=$request->name;
        $contact->email=$request->email;
        $contact->subject=$request->subject;
        $contact->mobile=$request->mobile;
        $contact->send_from='site';
        $contact->message=$request->message;
     //   $contact->type=0;
        $done = $contact->save();
        if($done){
            return redirect()->back()->with('success', __('api.ok'));
        }
        return redirect()->back()->with('errorMsg', __('api.whoops'))->withInput();
    }

}
