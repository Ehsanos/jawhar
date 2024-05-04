<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PromoCode;
use App\Models\Subadmin;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Networks;
use App\Models\GameServies;
use App\Models\ProductService;
use App\Models\PromoCodeTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;


class PromoCodeController extends Controller
{

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
        $items = PromoCode::filter()->orderBy('id', 'desc')->get();
        return view('admin.promo_codes.home', [
            'items' => $items,
        ]);
    }


    public function create()
    {
        $product_servics = ProductService::get();
        $networks = Networks::get();
        $game_servies = GameServies::get();
        
        return view('admin.promo_codes.create')->with(compact('product_servics' , 'networks' , 'game_servies'));
    }


    public function store(Request $request)
    {
        $roles = [
            'percent' => 'required',
            'status' => 'required',
            'target_type' => 'required',
            'quantity' => 'required',
            'max_usage' => 'required',
            'end_date' => 'required|date|after_or_equal:today',
        ];
        $this->validate($request, $roles);
            
        if(count($request->items) == 0){
            return redirect()->back();
        }
        for ($i = 0 ; $i<$request->quantity ; $i++){
            $item = new PromoCode();
            $item->code = Str::random(6);
            $item->status = $request->status;
            $item->target_type = $request->target_type;
            $item->percent = $request->percent;
            $item->max_usage = $request->max_usage;
            $item->end_date = $request->end_date;
            $item->save();
             
            foreach ($request->items as $one){
                $promo_code_target = new PromoCodeTarget();
                $promo_code_target->promo_code_id = $item->id;
                $promo_code_target->target_id = $one;
                $promo_code_target->target_type = $request->target_type;
                $promo_code_target->save();
            }
        }
        
        
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
        $product_servics = ProductService::get();
        $networks = Networks::get();
        $game_servies = GameServies::get();
        
        $item = PromoCode::where('id', $id)->first();
        
        $items = '';
        if($item->target_type == 1){
            $items = $product_servics;
        }elseif ($item->target_type == 2) {
            $items = $networks;
        }elseif ($item->target_type == 3) {
            $items = $game_servies;
        }
        return view('admin.promo_codes.edit', [
            'item' => $item,
            'product_servics' => $product_servics,
            'networks' => $networks,
            'game_servies' => $game_servies,
            'items' => $items,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PromoCode::query()->where('id', $id)->first();
         $roles = [
            'percent' => 'required',
            'status' => 'required',
            'target_type' => 'required',
            'max_usage' => 'required',
            'end_date' => 'required|date|after_or_equal:today',
        ];
        $this->validate($request, $roles);
            
        if(count($request->items) == 0){
            return redirect()->back();
        }
        
        $item->status = $request->status;
        $item->target_type = $request->target_type;
        $item->percent = $request->percent;
        $item->max_usage = $request->max_usage;
        $item->end_date = $request->end_date;
        $item->save();
         
        PromoCodeTarget::where('promo_code_id' , $id)->delete(); 
        foreach ($request->items as $one){
            $promo_code_target = new PromoCodeTarget();
            $promo_code_target->promo_code_id = $item->id;
            $promo_code_target->target_id = $one;
            $promo_code_target->target_type = $request->target_type;
            $promo_code_target->save();
        }
        
        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {
        $ad = PromoCode::query()->findOrFail($id);
        if ($ad) {
            PromoCode::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";    
    }
}
