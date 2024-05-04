<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Permission;


class RoleController extends Controller
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
        $roles = Permission::orderBy('id', 'desc')->get();
        return view('admin.roles.home', [
            'ads' =>$roles,
        ]);
    }

    public function create()
    {
        //
        return view('admin.roles.create');
    }


    public function store(Request $request)
    {
        //
        $roles = [
            'roleSlug' => 'required|unique:permissions',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $role= new Permission();
        $role->roleSlug = $request->roleSlug;

        foreach ($locales as $locale)
        {
            $role->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }
        $role->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    public function edit($id)
    {
        //
        $item = Permission::findOrFail($id);
        return view('admin.roles.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $roles = [
        //   'roleSlug' => 'required|url',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $role = Permission::query()->findOrFail($id);
        //$role->roleSlug = $request->get('roleSlug');
        foreach ($locales as $locale)
        {
            $role->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }
        $role->save();
        return redirect()->back()->with('status', __('cp.update'));
    }
  
}
