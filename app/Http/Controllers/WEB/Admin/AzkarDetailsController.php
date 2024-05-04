<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Language;
use App\Models\AzkarDetails;
use App\Models\Azkar;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Image;


class AzkarDetailsController extends Controller
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

    public function index(Request $request)
    {

            $items = AzkarDetails::query();

            if ($request->has('type')) {
                if ($request->get('type') != null)
                    $items->where('type',  $request->get('type'));
            }

            $items = $items->get();
            return view('admin.azkarDetails.home', [
                'items' => $items,
            ]);

    }

    public function create()
    {

           $azkar= Azkar::where('status','active')->get();
            return view('admin.azkarDetails.create',[
               'azkar'=>$azkar,
            ]);

    }


    public function store(Request $request)
    {
            $roles = [
                'details'     => 'required',
                'azkar_id'     => 'required|int',
                'repetition'     => 'required|int',
            ];
            $this->validate($request, $roles);

                        $item = new AzkarDetails();
                        $item->details = $request->details;
                        $item->azkar_id = $request->azkar_id;
                        $item->repetition = $request->repetition;
                        $item->save();

            return redirect()->back()->with('status', __('cp.create'));
            
        
    }



    public function edit($id)
    {

            $item = AzkarDetails::findOrFail($id);
           $azkar= Azkar::query()->get();
            return view('admin.azkarDetails.edit', [
                'item' => $item,
                'azkar'=>$azkar,
            ]);
    }

    public function update(Request $request, $id)
    {
        if (can('numbers')) {
            $roles = [
                'details'     => 'required',
                'repetition'     => 'required|int',
            ];
            $this->validate($request, $roles);
            $item =AzkarDetails::findOrFail($id);
                        $item->details = $request->details;
                        $item->repetition = $request->repetition;
                        $item->save();
            return redirect()->back()->with('status', __('cp.update'));
        }
    }


}
