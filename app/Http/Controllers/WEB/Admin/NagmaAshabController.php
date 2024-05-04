<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\AshabGamesCards;
use App\Models\NagmaAshab;
use App\Models\Store;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\Admin;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Networks;
use App\Models\NetworksCards;
use App\Models\Wifi;
use App\Models\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CardImport;


class NagmaAshabController extends Controller
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

        $nagma = NagmaAshab::all();

        return view('admin.nagma_ashab.home', [
            'nagma' => $nagma,
        ]);
    }


    public function create(Request $request)
    {
        $nagma_ashab_cards_ids = AshabGamesCards::all();

        return view('admin.nagma_ashab.create',[
            'nagma_ashab_cards_ids' => $nagma_ashab_cards_ids,
        ]);
    }


    public function store(Request $request)
    {
        //
        $roles = [
            'name' => 'required',
            'average' => 'required',
        ];

        $this->validate($request, $roles);

        $ids = '';
        if ($request->nagma_ashab_cards_ids) {
            foreach ($request->nagma_ashab_cards_ids as $ashab_cards_id) {
                $ids .= $ashab_cards_id . ',';
            }
        }

        $nagma = new NagmaAshab();
        $nagma->name =$request->name;
        $nagma->average =$request->average;
        $nagma->status =$request->status;
        $nagma->ashab_cards_ids = !empty($ids) ?  substr($ids, 0, -1) : "";
        $nagma->save();

        return redirect()->back()->with('status', __('cp.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function get_all_cards($id)
    {
        $nagma_ashab_cards_ids = AshabGamesCards::query()
            ->where("status_cart","1")
            ->where("ashab_game_id",$id)
            ->pluck("id","card_name")
            ->toArray();

        return response()->json($nagma_ashab_cards_ids);
    }


    public function edit($id)
    {
        //

        $nagma = NagmaAshab::findOrFail($id);
        $nagma_ashab_cards_ids = AshabGamesCards::all();

        return view('admin.nagma_ashab.edit', [
            'nagma' => $nagma,
            'nagma_ashab_cards_ids' => $nagma_ashab_cards_ids,
        ]);

    }


    public function update(Request $request, $id)
    {
        //
        $roles = [

            'name' => 'required',
            'average' => 'required',

        ];

        $this->validate($request, $roles);

        $ids = '';
        if ($request->nagma_ashab_cards_ids) {
            foreach ($request->nagma_ashab_cards_ids as $ashab_cards_id) {
                $ids .= $ashab_cards_id . ',';
            }
        }

        $nagma= NagmaAshab::query()->findOrFail($id);
        $nagma->name =$request->name;
        $nagma->status =$request->status;
        $nagma->average = $request->average;
        $nagma->ashab_cards_ids = !empty($ids) ?  substr($ids, 0, -1) : "";

        $nagma->save();

        return redirect()->back()->with('status', __('cp.update'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $nagma = NagmaAshab::query()->findOrFail($id);
        if ($nagma) {
            NagmaAshab::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }



}


