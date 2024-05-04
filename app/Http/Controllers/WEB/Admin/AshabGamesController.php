<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\AshabGames;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;

class AshabGamesController extends Controller
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
     //  $ashab_games = getAshabGames()->products;
        $games = AshabGames::orderBy('id', 'desc')->get();
        return view('admin.ashabGame.home', [  'games' => $games ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $games = getAshabGames()->products;
        return view('admin.ashabGame.create', ['lolo' => $games]);
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
        'game_id' => 'required',
        'game_status' => 'required',
        'game_tap' => 'required',
        'game_name' => 'required',
        //'game_num' => 'required',
    ];
        $this->validate($request, $roles);

        $rrrr = AshabGames::where("game_id",$request->game_id)->first();

        if(!isset($rrrr->id))
        {
            $item = new AshabGames();
            $item->game_id = $request->game_id;
            $item->game_status = $request->game_status;
            $item->game_tap = $request->game_tap;
            $item->game_name = $request->game_name;
            $item->game_num = $request->game_num;
            $item->game_text = $request->game_text;
            $item->save();

            return redirect()->back()->with('status', __('cp.create'));
        }
        else
        {
            return redirect()->back()->with('status', __('هذا الخيار مستعمل مسبقاً'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ashab_games = getAshabGames()->products;
        $item = AshabGames::findOrFail($id);
        return view('admin.ashabGame.edit', ['lolo' => $ashab_games,'item'=>$item]);


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $roles = [
            'game_id' => 'required',
            'game_status' => 'required',
            'game_tap' => 'required',
            'game_name' => 'required',
           // 'game_num' => 'required',
        ];
        $this->validate($request, $roles);
        $item = AshabGames::query()->findOrFail($id);

        $item->game_id = $request->game_id;
        $item->game_status = $request->game_status;
        $item->game_tap = $request->game_tap;
        $item->game_name = $request->game_name;
        $item->game_num = $request->game_num;
        $item->game_text = $request->game_text;
        $item->save();
        return redirect()->back()->with('status', __('cp.create'));
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
    }
}
