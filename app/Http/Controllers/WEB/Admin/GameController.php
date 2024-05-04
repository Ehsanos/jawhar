<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Game;
use App\Models\City;
use App\Models\Api;




class GameController extends Controller
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
        $games = Game::orderBy('id', 'desc')->get();
        return view('admin.games.home', [
            'games' => $games ,
        ]);
    }

    public function create()
    {
        $cities = City::where('status','active')->get();
        $apis = Api::get();
        return view('admin.games.create',['cities'=>$cities , 'apis'=>$apis]);
    }

    public function edit($id)
    {
                    $cities = City::where('status','active')->get();

        $item = Game::findOrFail($id);
        return view('admin.games.edit', [
            'item' => $item,
            'cities'=>$cities,
        ]);

    }

   public function store(Request $request)
    {
        // return '123';
        $roles = [
           'name' => 'required',
           'city_id' => 'required',
           'min_quantity' => 'required',
           'is_quantity' => 'required',
           'is_game_player' => 'required',
           'status' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $game= new Game();
        $game->name = $request->name;
        $game->city_id = $request->city_id;
        $game->min_quantity = $request->min_quantity;
        $game->is_quantity = $request->is_quantity;
        $game->is_game_player = $request->is_game_player;
        $game->status = $request->status;
        
        
        $game->api_id = isset($request->api_id)?$request->api_id:0;
        $game->target_id = isset($request->target_id)?$request->target_id:0;
        $game->price = isset($request->price)?$request->price:0;
        $game->commission = isset($request->commission)? $request->commission:10;
        
        
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
        return redirect(url('admin/games'))->with('status', __('cp.create'));
    }


    public function update(Request $request, $id)
    {
        //
        $roles = [
          'name' => 'required',
          'city_id' => 'required',
          'status' => 'required',
       //  'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $game = Game::query()->findOrFail($id);
        $game->name = $request->get('name');
        $game->city_id = $request->get('city_id');
        $game->status = $request->get('status');
        $game->min_quantity = $request->min_quantity;
        $game->is_quantity = $request->is_quantity;
        $game->is_game_player = $request->is_game_player;
        if($game->api_id){
        $game->commission = isset($request->commission)? $request->commission:10;
        if(($game->api_id == 5 || $game->api_id == 2) && isset($request->price)){
            $game->price = $request->price;
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

    public function getDataFromApi(Request $request , $id){
        $api = Api::where('id' , $id)->first();
        
        return callAPI(isset($request->url) ? $request->url :$api->url , $api->token , [] , 'GET' , $id);
    }

}
