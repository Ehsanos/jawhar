<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Setting;
use App\Models\WhatsappUsers;
use App\Models\User;
use Illuminate\Http\Request;

class WhatsappController extends Controller
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
        $whatsapp =WhatsappUsers::all();
        return view('admin.whatsapp.home', [
            'whatsapp' => $whatsapp,
        ]);
    }
    public function create()
    {
        $user = User::orderBy('id', 'desc')->get();
        return view('admin.whatsapp.create',[
            'user'=>$user
        ]);
    }
    public function edit($id)
    {
        $whatsapp =WhatsappUsers::findOrFail($id);
        $user = User::orderBy('id', 'desc')->get();
        return view('admin.whatsapp.edit', [
            'whatsapp' => $whatsapp,
            'user'=>$user,

        ]);
    }
    public function store(Request $request)
    {
        $roles = [
            'phone' => 'required|unique:whatsapp_users',
            'user_id' => 'required',
            'status' => 'required',
        ];
        if($request->status == 1)
        {
            $roles['percent'] = 'required|integer';
        }
        $this->validate($request, $roles);
        $whatsapp = new WhatsappUsers();
        $whatsapp->phone = $request->phone;
        $whatsapp->user_id = $request->user_id;
        $whatsapp->status = $request->status;

        if ($request->has('percent')) {
            $whatsapp->percent = $request->percent;
        }

        $whatsapp->save();
        return redirect()->back()->with('status', __('cp.create'));
    }
    public function update(Request $request, $id)
    {
            $roles = [
                'phone' => 'required|unique:whatsapp_users,phone,'.$id,
                'user_id' => 'required',
                'status' => 'required',

            ];
        if($request->status == 1)
        {
            $roles['percent'] = 'required|integer';
        }
        $this->validate($request, $roles);
        $whatsapp = WhatsappUsers::query()->findOrFail($id);
        $whatsapp->phone = $request->phone;
        $whatsapp->user_id = $request->user_id;
        $whatsapp->status = $request->status;
        if ($request->has('percent')) {
            $whatsapp->percent = $request->percent;
        }
        $whatsapp->save();
        return redirect()->back()->with('status', __('cp.create'));
    }
}
