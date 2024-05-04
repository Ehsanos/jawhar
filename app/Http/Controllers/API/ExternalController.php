<?php

namespace App\Http\Controllers\API;
use App\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\Setting;
use App\Models\Token;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;



class ExternalController extends Controller
{


    public function getSections()
    {

           $items =  numbersappGetSections();

        return response()->json(['status' => true, 'code' => 200, 'items' => $items ]);
    }
    
    public function getServiceCountries($id)
    {

           $items =  getServiceCountries($id);

        return response()->json(['status' => true, 'code' => 200, 'items' => $items ]);
    }
    
    public function getLiveNumber($section,$service)
    {

           $items =  getLiveNumber($section,$service);

        return response()->json(['status' => true, 'code' => 200, 'items' => $items ]);
    }
    public function getLiveNumber($id)
    {

           $items =  getLiveNumber($id);

        return response()->json(['status' => true, 'code' => 200, 'items' => $items ]);
    }



}
