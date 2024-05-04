<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\GameServies;
use App\Models\User;
use App\Models\NagmaGame;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\Admin;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CardImport;


class NagmaGameController extends Controller
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

        $nagma = NagmaGame::all();

        return view('admin.nagma_game.home', [
            'nagma' => $nagma,
        ]);
    }


    public function create(Request $request)
    {
       $games = GameServies::orderBy('game_id', 'desc')->get();
       $users = User::orderBy('id', 'desc')->get();
    
        return view('admin.nagma_game.create',[
            'games' => $games,
            'users' => $users,
        ]);
    }


    public function store(Request $request)
    {
        //
        $roles = [
            'user_id' => 'required',
            'average' => 'required',
        ];

        $this->validate($request, $roles);

        $ids = '';
        if ($request->nagma_game_ids) {
            foreach ($request->nagma_game_ids as $ashab_cards_id) {
                $ids .= $ashab_cards_id . ',';
            }
        }

        $nagma = new NagmaGame();
        $nagma->user_id =$request->user_id;
        $nagma->average =$request->average;
        $nagma->status =$request->status;
        $nagma->nagma_game_ids =','.$ids;
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
        $nagma = NagmaGame::findOrFail($id);

        $nagma_ashab_cards_ids = GameServies::all();
        $users = User::orderBy('id', 'desc')->get();
        return view('admin.nagma_game.edit', [
            'users' => $users,
            'nagma' => $nagma,
            'nagma_ashab_cards_ids' => $nagma_ashab_cards_ids,
        ]);

    }


    public function update(Request $request, $id)
    {
        //
        $roles = [

            'user_id' => 'required',
            'average' => 'required',

        ];

        $this->validate($request, $roles);

        $ids = '';
        if ($request->nagma_ashab_cards_ids) {
            foreach ($request->nagma_ashab_cards_ids as $ashab_cards_id) {
                $ids .= $ashab_cards_id . ',';
            }
        }

        $nagma= NagmaGame::query()->findOrFail($id);
        $nagma->user_id =$request->user_id;
        $nagma->average =$request->average;
        $nagma->status =$request->status;
        $nagma->nagma_game_ids =','.$ids;
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
        $nagma = NagmaGame::query()->findOrFail($id);
        if ($nagma) {
            NagmaGame::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }



}


