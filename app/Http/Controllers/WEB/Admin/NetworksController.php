<?php

namespace App\Http\Controllers\WEB\Admin;

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


class NetworksController extends Controller
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


    public function index($my_store_id = null)
    {

        $network = Networks::query();
        $admin = auth('admin')->user();
        $admin_perm = UserPermission::where('user_id',$admin->id)->first();

        if($my_store_id != null)
        {
            $wifi = Wifi::where("store_id",$my_store_id)->first();
            if(isset($wifi->id)) {
                $network->where('wifi_id', $wifi->id);
            }
        }

        if ($admin->city_id > 0){
            $network->where('city_id',$admin->city_id);

            if (isset($admin_perm->store_id) && $admin_perm->store_id > 0)
            {
                $lolo = Wifi::where("store_id",$admin_perm->store_id)->first();
                if(isset($lolo->id)){
                    $network->where('wifi_id',$lolo->id);
                }
            }

        }


        $network = $network->withCount('networksCards')->get();
        return view('admin.networks.home', [
            'network' => $network,
        ]);
    }
    public function getCards($id)
    {

        $networksCards = NetworksCards::where('network_id',$id)->where('is_used',0)->get();
        $network = Networks::all();
        $wifi = Wifi::all();

        return view('admin.networks.cards', [
            'networksCards' => $networksCards,
            'network' => $network,
            'wifi' => $wifi,
            'id' => $id,
        ]);
    }


    public function addCards(Request $request,$id)
    {

        $network = Networks::findOrFail($id);
        return view('admin.networks.addCards',[
            'network' => $network,
        ]);
    }


    public function storeCards(Request $request,$id)
    {
        $network = Networks::findOrFail($id);
        $roles = [

            'card_id' => 'required',
            'pin' => 'required',
            'password' => 'required',

        ];

        $this->validate($request, $roles);


        $newNetwork = new NetworksCards();

        $newNetwork->card_id =$request->card_id;
        $newNetwork->pin =$request->pin;
        $newNetwork->password =$request->password;
        $newNetwork->wifi_id =$network->wifi_id;
        $newNetwork->network_id =$id;
        $newNetwork->save();

        return redirect()->back()->with('status', __('cp.create'));
    }
    public function create(Request $request)
    {
        $network = Networks::all();

        if(isset($request->my_store_id))
        {
            $wifi = Wifi::where("store_id",$request->my_store_id);
        }
        else
        {
            $wifi = Wifi::query();
        }
        $admin = auth('admin')->user();
        $admin_perm = UserPermission::where('user_id',$admin->id)->first();
        if ($admin->city_id > 0){
            $wifi->where('city_id',$admin->city_id)->where("Status",'active');

            if (isset($admin_perm->store_id ) && $admin_perm->store_id > 0)
            {
                $lolo = Wifi::where("store_id",$admin_perm->store_id)->first();
                if(isset($lolo->id)){
                    $wifi->where('id',$lolo->id);
                }
            }


        }

        $wifi = $wifi->get();

        return view('admin.networks.create',[
            'network' => $network,
            'wifi' => $wifi,
        ]);
    }


    public function store(Request $request)
    {
        //
        $roles = [

            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            'name' => 'required',
            'wifi_id' => 'required',
            'type' => 'required',
            'price' => 'required',
            'is_dollar' => 'required',

        ];

        $this->validate($request, $roles);


        $network = new Networks();
        $network->name =$request->name;
        $network->wifi_id =$request->wifi_id;
        $network->is_dollar = $request->is_dollar;
        $network->price =$request->price;
        $wifi = Wifi::where('id',$request->wifi_id)->first();
        $network->city_id =$wifi->city_id;
        $network->type =$request->type;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/networks/$file_name");
            $network->image = $file_name;
        }

        $network->save();

        return redirect()->back()->with('status', __('cp.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //

        $network = Networks::findOrFail($id);
        $wifi = Wifi::all();

        return view('admin.networks.edit', [
            'network' => $network,
            'wifi' => $wifi,

        ]);

    }


    public function update(Request $request, $id)
    {
        //
        $roles = [

            //   'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            'name' => 'required',
            //    'wifi_id' => 'required',

        ];

        $this->validate($request, $roles);


        $network= Networks::query()->findOrFail($id);;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/networks/$file_name");
            $network->image = $file_name;
        }

        $network->name =$request->name;
        $network->is_dollar = $request->is_dollar;
        $network->price =$request->price;

        $network->save();

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
        $network = Networks::query()->findOrFail($id);
        if ($network) {
            Networks::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }




    public function addFile($id)
    {
        //

        $network = Networks::findOrFail($id);

        return view('admin.networks.addFile', [
            'network' => $network,

        ]);

    }


    public function storeFile(Request $request ,$id)
    {
        $roles =  [

            'file'=>'required|max:50000|mimes:xlsx,xls'
        ];

        $this->validate($request, $roles);


        //return $request->file('file');
        $item= new ImportFile();
        $file = $request->file('file');
        $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . ".xlsx";
        // $name = time().$file->getClientOriginalName();

        $file->move(public_path().'/uploads/', $file_name);
        $item->file_name = $file_name;
        $item->network_id = $id;
        $item->save();
        return redirect()->back()->with('status', __('cp.we will precess your file in some minites'));


    }



    public function startImport(Request $request)
    {

        $files = ImportFile::where('status', 1)->get();
        //  return $files;
        if($files->count() >0){
            ///  return $files;
            foreach($files as $file){

                $files = ImportFile::where('id', $file->id)->update(['status'=>2]);
                $network= Networks::query()->findOrFail($file->network_id);
                // return $network;
                Excel::import(new CardImport($network), public_path().'/uploads/'.$file->file_name);
                // Excel::import(new UsersImport, public_path().'/uploads/excel/'.$file->file_name);

                $files = ImportFile::where('id', $file->id)->update(['status'=>3]);
            }

            return 'تمت العملية بنجاح';

        }
        else{
            return redirect()->back()->with('error', "لا يوجد ملفات لرفعها");
        }
    }



}


