<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\MonthlyRank;
use App\Models\Match;
use App\Models\UserGuess;
use App\Models\Wallet;
use App\Models\Notifiy;
use App\Models\Token;


class RanksController extends Controller
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
        $ranks = MonthlyRank::orderBy('id', 'desc')->get();
        return view('admin.ranks.home', [
            'ranks' =>$ranks,
        ]);
    }


    public function create()
    {

        return view('admin.ranks.create');
    }


    public function store(Request $request)
    {
        //
        $roles = [
            'month' => 'required',
            'year' => 'required',
        ];
        $this->validate($request, $roles);
$check=MonthlyRank::where(['month'=>$request->month, 'year'=>$request->year])->first();
if($check){
     return redirect()->back()->with('error', __('cp.duplicateMonth'));
}
        $matchesIds =Match::where('status','active')->whereMonth('match_date', '=',$request->month)->whereYear('match_date', '=', $request->year)->pluck('id')->toArray(); 
        $ranks = UserGuess::where('used',0)->whereIn('match_id', $matchesIds)->groupBy('user_id')->with('user')->selectRaw('*,sum(guess) as points')->orderByRaw('SUM(guess) DESC')->limit(3)->get();
if ($ranks->count() == 3) {
     $new= new MonthlyRank();
     $new->user_id = $ranks[2]->user_id;
     $new->month = $request->month;
     $new->year = $request->year;
     $new->rank = '3rd Place';
     $new->amount = $this->settings->monthly_prize3;
     $new->save();
      $wallet = new Wallet();
       $wallet->user_id = $ranks[2]->user_id;
       $wallet->type =1;// income
       $wallet->target_id =0;// rank
       $wallet->target_type =0;// Rank
       $wallet->amount =$this->settings->monthly_prize3;
       $wallet->save();
        $message2 =  __('cp.MonthlyPrize3ed')." for Month ".$request->month."/".$request->year ;
        $tokens_android = Token::where('user_id',$ranks[2]->user_id)->where('device_type','android')->pluck('fcm_token')->toArray();
        $tokens_ios = Token::where('user_id',$ranks[2]->user_id)->where('device_type','ios')->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens_android, $tokens_ios,  $message2);
        $notifiy = new Notifiy();
        $notifiy->user_id =$ranks[2]->user_id;
        $notifiy->message = $message2;
        $notifiy->save();
     
     $new= new MonthlyRank();
     $new->user_id = $ranks[1]->user_id;
     $new->month = $request->month;
     $new->year = $request->year;
     $new->rank = '2nd Place';
    $new->amount = $this->settings->monthly_prize2;
     $new->save();
     $wallet = new Wallet();
       $wallet->user_id = $ranks[1]->user_id;
       $wallet->type =1;// income
       $wallet->target_id =0;// rank
       $wallet->target_type =0;// Rank
       $wallet->amount =$this->settings->monthly_prize2;
       $wallet->save();
        $message2 =  __('cp.MonthlyPrize2nd')." for Month ".$request->month."/".$request->year ;
        $tokens_android = Token::where('user_id',$ranks[1]->user_id)->where('device_type','android')->pluck('fcm_token')->toArray();
        $tokens_ios = Token::where('user_id',$ranks[1]->user_id)->where('device_type','ios')->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens_android, $tokens_ios,  $message2);
        $notifiy = new Notifiy();
        $notifiy->user_id =$ranks[1]->user_id;
        $notifiy->message = $message2;
        $notifiy->save();
     
     $new= new MonthlyRank();
     $new->user_id = $ranks[0]->user_id;
     $new->month = $request->month;
     $new->year = $request->year;
     $new->rank = '1st Place';
     $new->amount = $this->settings->monthly_prize1;
     $new->save();
     $wallet = new Wallet();
       $wallet->user_id = $ranks[0]->user_id;
       $wallet->type =1;// income
       $wallet->target_id =0;// rank
       $wallet->target_type =0;// Rank
       $wallet->amount =$this->settings->monthly_prize1;
       $wallet->save();
             $message2 =  __('cp.MonthlyPrize1st')." for Month ".$request->month."/".$request->year ;
        $tokens_android = Token::where('user_id',$ranks[0]->user_id)->where('device_type','android')->pluck('fcm_token')->toArray();
        $tokens_ios = Token::where('user_id',$ranks[0]->user_id)->where('device_type','ios')->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens_android, $tokens_ios,  $message2);
        $notifiy = new Notifiy();
        $notifiy->user_id =$ranks[0]->user_id;
        $notifiy->message = $message2;
        $notifiy->save();

}

        return redirect()->back()->with('status', __('cp.create'));
    }

    public function edit($id)
    {
        //
        $item = MonthlyRank::findOrFail($id);
        return view('admin.ads.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $roles = [
            'link' => 'required|url',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['details_' . $locale] = 'required';
            $roles['title_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $ranks = MonthlyRank::query()->findOrFail($id);
        $ranks->link = $request->get('link');
        foreach ($locales as $locale)
        {
            $ranks->translateOrNew($locale)->details = $request->get('details_' . $locale);
            $ranks->translateOrNew($locale)->title = $request->get('title_' . $locale);
        }

        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/ads/".$file_name);
            $ranks->image = $file_name;
        }

        $ranks->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        //
        $ranks = MonthlyRank::query()->findOrFail($id);
        if ($ranks) {
            MonthlyRank::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
}
