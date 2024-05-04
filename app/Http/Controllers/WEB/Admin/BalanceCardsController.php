<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\BalanceCard;
use App\Models\UserWallet;


class BalanceCardsController extends Controller
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
    public function index(Request $request)
    {
         $balanceCards = BalanceCard::query();
        if ($request->has('price')) {
            if ($request->get('price') != null)
                $balanceCards->where('price', $request->get('price'));
        }
        if ($request->has('serial_number')) {
            if ($request->get('serial_number') != null)
                $balanceCards->where('serial_number', 'like', '%' . $request->get('serial_number') . '%');
        }
        
        $balanceCards = $balanceCards->where('is_used', 0)->orderBy('id', 'desc')->paginate(30);
        return view('admin.balanceCards.home', [
            'balanceCards' =>$balanceCards,
        ]);
    }
    public function newbalanceCards()
    {
        $balanceCards = UserWallet::where('order_id',0)->where('type',0)->orderBy('id', 'DESC')->get();
        return view('admin.balanceCards.newbalanceCards', [
            'balanceCards' =>$balanceCards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.balanceCards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $roles = [
           'price' => 'required',
           'quantity' => 'required',
           'currency' => 'required',
        ];
        $this->validate($request, $roles);
        set_currency_now($request->currency);
        $wellet_J_In = UserWallet::where('user_id',2212)->where('type', 0)->sum('total_price');
        $wellet_J_Out  = UserWallet::where('user_id',2212)->where('type', 1)->sum('total_price');
        $wellet_j_balanceIn = $wellet_J_In - $wellet_J_Out ;
        $price = $request->price;
        $total_quantity = $request->quantity;
       $total_price = $price * $total_quantity;
        $data =[];
        if ($total_price < $wellet_j_balanceIn)
        {
            for($i =0;$i<$request->quantity ;$i++){
                $password = mt_rand(10000000,99999999);
                $serial_numberd = mt_rand(100000,999999) .str_random(2) ;
                $wallet_j = new UserWallet();
                $wallet_j->user_id = 2212;
                $wallet_j->order_id = 0;
                $wallet_j->title = 'بطاقة رصيد';
                $wallet_j->details = 'تم انشاء بطاقة رصيد ' .$serial_numberd;
                $wallet_j->total_price = $request->price;
                $wallet_j->type = 1;  //0=in 1=out
                $wallet_j->save();

                $data[] = [
                    'price' => $request->price,
                    'serial_number' => $serial_numberd,
                    'password' => $password,
                    'currency' => $request->currency,
                ];
            }
        }else
        {
            set_currency_now("");
            return redirect()->back()->with('error', __('cp.error'));
        }

        if(!empty($data)){
                BalanceCard::insert($data);
        }

        set_currency_now("");
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
        $item = BalanceCard::findOrFail($id);
        return view('admin.balanceCards.edit', [
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        //
        $roles = [
                   'price' => 'required',
                   'serial_number' => 'required',
                   'password' => 'required',
                    'currency' => 'required',
        ];

        $this->validate($request, $roles);
        $chceckSerialNumber= BalanceCard::where('serial_number',$request->serial_number)->first();
        if($chceckSerialNumber){
                    return redirect()->back()->with('status', __('لا يمكن التعديل هذا الرقم مستخدم مسبقا'));

        }
        $balanceCards = BalanceCard::query()->findOrFail($id);
        $balanceCards->serial_number = $request->serial_number;
        $balanceCards->price = $request->price;
        $balanceCards->password = $request->password;
        $balanceCards->currency = $request->currency;

        $balanceCards->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        $balanceCards = BalanceCard::query()->findOrFail($id);
        if ($balanceCards) {
            $wallet_j = new UserWallet();
            $wallet_j->user_id = 2212;
            $wallet_j->order_id = 0;
            $wallet_j->title = 'بطاقة رصيد';
            $wallet_j->details = 'تم حذف بطاقة رصيد ' .$balanceCards->serial_number;
            $wallet_j->total_price = $balanceCards->price;
            $wallet_j->type = 0;  //0=in 1=out
            $wallet_j->save();
            BalanceCard::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
}
