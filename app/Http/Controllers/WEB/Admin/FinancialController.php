<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Permission;
use App\Models\Setting;
use App\Models\GameRequest;
use App\Models\NetworksCardsRequest;
use App\Models\CourseRequest;
use App\Models\RequestMobileBalance;
use App\Models\BalanceCard;

use DB;
use App\Models\Contact;
use App\Models\Booking;
use App\Models\UserWallet;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class FinancialController extends Controller
{

    public function  index(Request $request)
    {
        set_currency_now($request->currency);
        //dd(app(UserWallet::class)->getTable());

        $admin=Admin::findOrFail(auth()->guard('admin')->user()->id);
        $GameRequest = GameRequest::query()->where("currency",get_currency_now());
        $networksCardsRequest = NetworksCardsRequest::query()->where("currency",get_currency_now());
        $balanceInJawhar=UserWallet::query();
        $balanceOutJawhar=UserWallet::query();
        $balanceInUsers=UserWallet::query();
        $balanceOutUsers=UserWallet::query();
        $balanceInohme=UserWallet::query();
        $balanceOutohme=UserWallet::query();
        $balance_cards=BalanceCard::where('is_used',1)->where("currency",get_currency_now())->sum('price');
        $balance_cards_price=BalanceCard::where('is_used',0)->where("currency",get_currency_now())->sum('price');
        $publicServicesRequest = RequestMobileBalance::query()->where("currency",get_currency_now());
        $courseRequest = CourseRequest::query()->where("currency",get_currency_now());
        if($request->has('date_from') || $request->has('date_to'))
        {
            if ($request->get('date_from') != null)
            {
                    $GameRequest->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $networksCardsRequest->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceInJawhar->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceOutJawhar->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceInUsers->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceOutUsers->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceInohme->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $balanceOutohme->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $publicServicesRequest->where('created_at','>=',$request->get('date_from')." 00:00:00");
                    $courseRequest->where('created_at','>=',$request->get('date_from')." 00:00:00");
            }
            if ($request->get('date_to') != null)
            {
                    $GameRequest->where('created_at','>=',$request->get('date_to')." 00:00:00");
                    $networksCardsRequest->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceInJawhar->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceOutJawhar->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceInUsers->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceOutUsers->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceInohme->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $balanceOutohme->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $publicServicesRequest->where('created_at','<=',$request->get('date_to')." 00:00:00");
                    $courseRequest->where('created_at','<=',$request->get('date_to')." 00:00:00");
            }
        }

        $GameRequest = $GameRequest->where('status','!=',3);

        $networksCardsRequest = $networksCardsRequest->where('status','!=',3);

        $balanceInJawhar=$balanceInJawhar->where('user_id',1)->where('type',0)->sum('total_price');
        $balanceOutJawhar=$balanceOutJawhar->where('user_id',1)->where('type',1)->sum('total_price');
        $balanceInUsers=$balanceInUsers->where('type',0)->sum('total_price');
        $balanceOutUsers=$balanceOutUsers->where('type',1)->sum('total_price');

        $balanceInohme=$balanceInohme->where('user_id',2212)->where('type',0)->sum('total_price');
        $balanceOutohme=$balanceOutohme->where('user_id',2212)->where('type',1)->sum('total_price');

        $jawharstores = $balanceInJawhar - $balanceOutJawhar;

        $ohmeuser = $balanceInohme - $balanceOutohme;

        $jawharUsers = $balanceInUsers - $balanceOutUsers - $jawharstores - $ohmeuser;

        $userandjawhar = $jawharstores + $jawharUsers ;

        $jawharstores = number_format($jawharstores, 2, '.', '');
        $ohmeuser = number_format($ohmeuser, 2, '.', '');
        $jawharUsers = number_format($jawharUsers, 2, '.', '');
        $userandjawhar = number_format($userandjawhar, 2, '.', '');

        $publicServicesRequest = $publicServicesRequest->where('status','!=',3);

        $courseRequest = $courseRequest->where('status','!=',3);

        if($admin->city_id > 0)
        {
           $GameRequest->where('city_id',$admin->city_id );
           $networksCardsRequest->where('city_id',$admin->city_id );
           $courseRequest->where('city_id',$admin->city_id );
           $publicServicesRequest->where('city_id',$admin->city_id );
        }
            $GameRequestCount = $GameRequest->sum('price');
            $networksCardsRequestCount = $networksCardsRequest->sum('price');
            $courseRequest = $courseRequest->sum('price');
            $publicServicesRequest = $publicServicesRequest->sum('balance');

            set_currency_now("");

            return view('admin.financial.home',[
                'admin'=>$admin,
                'jawharstores'=>$jawharstores,
                'jawharUsers'=>$jawharUsers,
                'ohmeuser'=>$ohmeuser,
                'userandjawhar'=>$userandjawhar,
                'GameRequestCount'=>$GameRequestCount,
                'networksCardsRequestCount'=>$networksCardsRequestCount,
                'publicServicesRequest'=>$publicServicesRequest,
                'courseRequest'=>$courseRequest,
                'balanceOutUsers'=>$balanceOutUsers,
                'balance_cards'=>$balance_cards,
               'balance_cards_price'=> $balance_cards_price,
                ]);
    }

}
