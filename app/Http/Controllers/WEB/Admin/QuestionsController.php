<?php


namespace App\Http\Controllers\WEB\Admin;
use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use App\Models\Setting;
use App\Models\Language;
use App\Models\Question;

class QuestionsController extends Controller
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
    public function index(Request $request)
    {
        $items = Question::get();

        return view('admin.questions.home', [
            'items' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.questions.create');
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
           // 'logo' => 'required|image|mimes:jpeg,jpg,png',
            // 'name'     => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['question_' . $locale] = 'required';
            $roles['answer_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $item= new Question();

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->question = $request->get('question_' . $locale);
            $item->translateOrNew($locale)->answer = $request->get('answer_' . $locale);
        }


        $item->save();
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
        $item = Question::findOrFail($id);
        return view('admin.questions.edit', [
            'item' => $item,
        ]);
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
            // 'logo' => 'required|image|mimes:jpeg,jpg,png',
            // 'name'     => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['question_' . $locale] = 'required';
            $roles['answer_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $item = Question::findOrFail($id);
        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->question = $request->get('question_' . $locale);
            $item->translateOrNew($locale)->answer = $request->get('answer_' . $locale);
        }
        $item->save();

        return redirect()->back()->with('status', __('common.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $item = Question::findOrFail($id);
        if ($item) {
            Question::where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
}
