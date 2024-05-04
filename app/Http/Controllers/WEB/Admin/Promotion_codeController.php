<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Language;
use App\Models\PromotionCode;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Image;


class Promotion_codeController extends Controller
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
        $items = PromotionCode::all();
        return view('admin.promotion.home', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('admin.promotion.create');
    }


    public function store(Request $request)
    {
        $roles = [
            'name'     => 'required',
            'start'     => 'required',
            'end'     => 'required',
            'discount'     => 'required|numeric',
        ];
        $this->validate($request, $roles);


        $promo = new PromotionCode();
        $promo->name = $request->name;
        $promo->start = $request->start;
        $promo->end = $request->end;
        $promo->description = $request->description;
        $promo->discount = $request->discount;

        $promo->save();
        return redirect()->back()->with('status', __('common.create'));

    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = PromotionCode::findOrFail($id);
        return view('admin.promotion.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {

        $roles = [
            'name'     => 'required',
            'start'     => 'required',
            'end'     => 'required',
            'discount'     => 'required|numeric',
        ];
        $this->validate($request, $roles);


        $item = PromotionCode::query()->findOrFail($id);

        $item->name = $request->name;
        $item->start = $request->start;
        $item->end = $request->end;
        $item->description = $request->description;
        $item->discount = $request->discount;
        $item->save();

        return redirect()->back()->with('status', __('common.update'));

    }

    public function destroy($id)
    {
        $item = PromotionCode::query()->findOrFail($id);
        if ($item) {
            PromotionCode::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

    public function changeStatus(Request $request)
    {
        dd($request->IDsArray) ;
        if ($request->event == 'delete') {
            PromotionCode::query()->whereIn('id', $request->IDsArray)->delete();
        } else {
            PromotionCode::query()->whereIn('id', $request->IDsArray)->update(['status' => $request->event]);
        }
        return $request->event;
    }

}
