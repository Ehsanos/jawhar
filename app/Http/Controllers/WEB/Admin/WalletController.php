<?php

namespace App\Http\Controllers\WEB\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\Admin;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\UserWallet;

class WalletController extends Controller
{

 
   
 
    public function index()
    {
        $wallet = UserWallet::where('title','خدمة')->orderBy('created_at', 'desc')->get();

        return view('admin.soldServices.home', [
            'wallet' => $wallet,

        ]);
    }


}
