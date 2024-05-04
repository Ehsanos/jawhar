<?php


namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\SubscribeEmail;
use App\Models\Language;
use App\Models\Category;

class SubscribeEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->locales = Language::all();
        $this->settings = Setting::query()->first();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);
    }
    public function index()
    {
        //
        $subscribe = SubscribeEmail::all();
        
        return view('admin.subscribeemail.home', [
            'subscribe' => $subscribe, 
        ]);
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = [
            'email' => 'required|email',
            
        ];
      
        $this->validate($request, $roles);
        $subscribe= new SubscribeEmail();
        $subscribe->email= $request->email;     
        $subscribe->save();
        return redirect()->back()->with('status', __('cp.sucsess'));

    }



}
