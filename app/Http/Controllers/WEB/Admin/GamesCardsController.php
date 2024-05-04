<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\AshabGames;
use App\Models\AshabGamesCards;
use Illuminate\Http\Request;

class GamesCardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $games_cards = getAshabGameInfo($request->gameId);

        $domastraction_ids = [];

        foreach ($games_cards->products as $one_pro)
        {
            $domastraction_ids[] = $one_pro->denomination_id;

            $ss = AshabGamesCards::where("denomination_id",$one_pro->denomination_id)->first();

            if(isset($ss->id))
            {
                $ss->ashab_game_id = $request->gameId;
                $ss->denomination_id = $one_pro->denomination_id;
                $ss->card_name = $request["card_name_".$one_pro->denomination_id];
                $ss->status_cart = isset($request["status_cart_".$one_pro->denomination_id]) && $request["status_cart_".$one_pro->denomination_id] == "on" ? 1 : 0;
                $ss->price = $request["price_".$one_pro->denomination_id];
                $ss->save();
            }
            else
            {
                $ss = new AshabGamesCards();
                $ss->ashab_game_id = $request->gameId;
                $ss->denomination_id = $one_pro->denomination_id;
                $ss->card_name = $request["card_name_".$one_pro->denomination_id];
                $ss->status_cart = isset($request["status_cart_".$one_pro->denomination_id]) && $request["status_cart_".$one_pro->denomination_id] == "on" ? 1 : 0;
                $ss->price = $request["price_".$one_pro->denomination_id];
                $ss->save();
            }

        }

        AshabGamesCards::where("ashab_game_id",$request->gameId)->whereNotIn("denomination_id",$domastraction_ids)->delete();

        session()->put("saved","done");
        return redirect(app()->getLocale().'/admin/gamecard/'.$request->gameId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = getAshabGameInfo($id);
        $data = AshabGamesCards::where("ashab_game_id",$id)->get();
        return view('admin.gamecard.home',['gameId' => $id,'lolo' => $response->products, 'jojo' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
