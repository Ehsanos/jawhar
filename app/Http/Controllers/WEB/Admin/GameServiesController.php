<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Game;
use App\Models\GameServies;
use App\Models\Api;




class GameServiesController extends Controller
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
        $games = GameServies::orderBy('id', 'desc')->get();
        return view('admin.gameServies.home', [
            'games' => $games ,
        ]);
    }

    public function create()
    {
        $games = Game::orderBy('id', 'desc')->get();
        $apis = Api::get();

        return view('admin.gameServies.create', [
            'games' => $games ,
             'apis'=>$apis
        ]);
    }

    public function edit($id)
    {
        $item = GameServies::findOrFail($id);
                $games = Game::orderBy('id', 'desc')->get();

        return view('admin.gameServies.edit', [
            'item' => $item,
             'games' => $games ,
        ]);

    }

       public function store(Request $request)
    {
        // return $request;
        //
        $roles = [
           'game_id' => 'required',
           'size' => 'required',
           'price' => 'required',
          // 'purchasing_price' => 'required',
           'is_dollar' => 'required',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);
         $apis = Api::where('id',$request->api_id)->first();
        $game= new GameServies();
        $game->game_id = $request->game_id;
        $game->size = $request->size;
        $game->purchasing_price = $request->purchasing_price ?? $request->price;
        $game->price = $request->price;
        $game->is_dollar =$request->is_dollar;
        if($apis){
            $game->api_name = $apis->name;
        }else{
           $game->api_name = 'ليست من مزود خارجي'; 
        }
        $game->api_id = isset($request->api_id)?$request->api_id:0;
        if(isset($request->api_id)){
            if($request->api_id == 2){
                $game->target_id = isset($request->denomination_id)?$request->denomination_id:0;
            }else{
                $game->target_id = isset($request->target_id)?$request->target_id:0;
            }
        }
        
        $game->api_product_name = $request->api_product_name ;
        $game->price = isset($request->price)?$request->price:0;
        $game->commission = isset($request->commission)? $request->commission:10;
        
        
        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }
        $game->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function update(Request $request, $id)
    {
        //
        $roles = [
           'game_id' => 'required',
           'size' => 'required',
            'purchasing_price' => 'required',
        //   'price' => 'required',
           'is_dollar' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $game = GameServies::query()->findOrFail($id);
        $game->game_id = $request->game_id;
        $game->size = $request->size;
        $game->purchasing_price = $request->purchasing_price ?? $request->price ?? 0;
        // $game->price = $request->price;
        $game->is_dollar =$request->is_dollar;
        if($game->api_id){
        $game->commission = isset($request->commission)? $request->commission:10;
        if(($game->api_id == 5 || $game->api_id == 2) && isset($request->price)){
            $game->price = $request->price;
        }else {
           $game->price = $game->price;
        }
        }
        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }

        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/games/".$file_name);
            $game->image = $file_name;
        }

        $game->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


}
