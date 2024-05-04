<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\AshabLog;
use App\Models\AshabRequest;
use App\Models\Language;
use App\Models\Notify;
use App\Models\Setting;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use Illuminate\Http\Request;

class AshabLogController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
    
        $ashab_log = AshabLog::orderBy('id', 'desc')->limit(50)->get();
        foreach($ashab_log as $key=>$one_log)
        {

            $data_denomination = getAshabGameInfo($one_log->game_id);
            $one_log->game_id = $data_denomination->name;
            foreach ($data_denomination->products as $one_pro)
            {
                if($one_pro->denomination_id == $one_log->denomination_id)
                {
                    $one_log->denomination_id = $one_pro->product_name;
                    break;
                }
            }

        }


        return view('admin.AshabLog.home', [  'ashab_log' => $ashab_log  ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {

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
