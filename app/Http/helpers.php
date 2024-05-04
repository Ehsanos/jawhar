<?php
use App\Models\Setting;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\User;
use Illuminate\Support\Facades\Cache;


function callAPI($endpointURL,$api_id, $apiKey, $postFields = [], $requestType = 'POST' ) {
    if($api_id == '1'|| $api_id== '3'){
        $auth = 'api-token: '.$apiKey;
    }elseif ($api_id == '2'){
        $auth = 'Authorization: Bearer '.$apiKey;
    }elseif($api_id == '5' || $api_id == '8'){
        $auth = 'api-token: '.$apiKey;
    }

    $curl = curl_init($endpointURL);
    curl_setopt_array($curl, array(
        CURLOPT_CUSTOMREQUEST  => $requestType,
        CURLOPT_POSTFIELDS     => json_encode($postFields),
        CURLOPT_HTTPHEADER     => array($auth, 'Content-Type: application/json'),
        CURLOPT_RETURNTRANSFER => true,
    ));

    $response = curl_exec($curl);
    $curlErr  = curl_error($curl);

    curl_close($curl);

    if ($curlErr) {
        return (object) ["status"=>false,"message"=>$curlErr];
    }

    $error = handleError($response);
    if ($error) {
        return (object) ["status"=>false,"message"=>$error];
    }
    $res = json_decode($response);

    return (object) ["status"=>true,"response"=>$res];
}
function handleError($response) {

    $json = json_decode($response);
    if (isset($json->IsSuccess) && $json->IsSuccess == true) {
        return null;
    }

    //Check for the errors
    if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
        $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
        $blogDatas = array_column($errorsObj, 'Error', 'Name');

        $error = implode(', ', array_map(function ($k, $v) {return "$k: $v";
        }, array_keys($blogDatas), array_values($blogDatas)));
    } else if (isset($json->Data->ErrorMessage)) {
        $error = $json->Data->ErrorMessage;
    }

    if (empty($error)) {
        $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
    }

    return $error;
}



function verify_android_in_app($signed_data, $signature, $public_key_base64)
{

	$key =	"-----BEGIN PUBLIC KEY-----\n".
		chunk_split($public_key_base64, 64,"\n").
		'-----END PUBLIC KEY-----';
	//using PHP to create an RSA key
	$key = openssl_get_publickey($key);
	//$signature should be in binary format, but it comes as BASE64.
	//So, I'll convert it.
	$signature = base64_decode($signature);
	//using PHP's native support to verify the signature
	$result = openssl_verify(
			$signed_data,
			$signature,
			$key,
			OPENSSL_ALGO_SHA1);
	if (0 === $result)
	{
		return false;
	}
	else if (1 !== $result)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function verify_app_store_in_app($receipt, $is_sandbox)
{

	//$sandbox should be TRUE if you want to test against itunes sandbox servers
	if ($is_sandbox)
		$verify_host = "ssl://sandbox.itunes.apple.com";
	else
		$verify_host = "ssl://buy.itunes.apple.com";

	$json='{"receipt-data" : "'.$receipt.'" }';
	//opening socket to itunes
	$fp = fsockopen ($verify_host, 443, $errno, $errstr, 30);
	if (!$fp)
	{
		// HTTP ERROR
		return false;
	}
	else
	{

		//iTune's request url is /verifyReceipt
		$header = "POST /verifyReceipt HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($json) . "\r\n\r\n";
		fputs ($fp, $header . $json);
		$res = '';
		while (!feof($fp))
		{

			$step_res = fgets ($fp, 1024);
			$res = $res . $step_res;
		}
		fclose ($fp);
		//taking the JSON response
		$json_source = substr($res, stripos($res, "\r\n\r\n{") + 4);
		//decoding
		$app_store_response_map = json_decode($json_source);
		return $app_store_response_map;
		$app_store_response_status = $app_store_response_map->{'status'};
		if ($app_store_response_status == 0)//eithr OK or expired and needs to synch
		{
		    return 2222;
			//here are some fields from the json, btw.
			$json_receipt = $app_store_response_map->{'receipt'};
			$transaction_id = $json_receipt->{'transaction_id'};
			$original_transaction_id = $json_receipt->{'original_transaction_id'};
			$json_latest_receipt = $app_store_response_map->{'latest_receipt_info'};
			return true;
		}
		else
		{
		    return 333;
			return false;
		}
	}
}

function post($url, $data, $headerArray = array())
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if (array() === $headerArray)
        curl_setopt($curl, CURLOPT_HTTPHEADER,["Content-type:application/json;charset='utf-8'","Accept:application/json"]);

    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function can($permission)
{
    //  $user = auth()->user();

    $userCheck = auth()->guard('admin')->check();
   $user='';

if($userCheck==false)
{
    return redirect('admin/login');
}
else
{
  $user=  auth()->guard('admin')->user();
}





    /*
        $users = User::where('status', 1)->get();
        $users->map(function ($item, $key) {
            return ucfirst($item->name);
        });
        dd($users);
    */


    if ($user->id == 1) {
        return true;
    }

//Cache::forget('permissions_' . $user->id);

        $minutes = 5;
    $permissions = Cache::remember('permissions_' . $user->id, $minutes, function () use ($user) {

        return explode(',', $user->permission->permission);

    });


    $permissions = array_flatten($permissions);
    return in_array($permission, $permissions);

    //@if(can('reservations.panel'))
}

function is_one_institute()
{
    $boo = true;
    $soso = false;

    $user = auth()->guard('admin')->user();

    if ($user->id == 1) {
        return true;
    }

        if(isset($user->id))
        {
            $lolo = explode(',', $user->permission->permission);

            foreach ($lolo as $koko)
            {
                if($koko == "institutes")
                {
                    $soso = true;
                    break;
                }
            }

            if($soso)
            {
                if(isset( $user->permission->institute_id ) && $user->permission->institute_id != "0")
                {
                    $boo = false;
                }
            }
        }

    return $boo;
}

function get_one_institute()
{
    $boo = 0;
    $soso = false;

    $user = auth()->guard('admin')->user();

    if(isset($user->id))
    {
        $lolo = explode(',', $user->permission->permission);

        foreach ($lolo as $koko)
        {
            if($koko == "institutes")
            {
                $soso = true;
                break;
            }
        }

        if($soso)
        {
            if(isset( $user->permission->institute_id ) && $user->permission->institute_id != "0")
            {
                $boo = $user->permission->institute_id;
            }
        }
    }

    return $boo;
}

function is_one_public_services()
{
    $boo = true;
    $soso = false;

    $user = auth()->guard('admin')->user();

    if ($user->id == 1) {
        return true;
    }

    if(isset($user->id))
    {
        $lolo = explode(',', $user->permission->permission);

        foreach ($lolo as $koko)
        {
            if($koko == "publicServices")
            {
                $soso = true;
                break;
            }
        }

        if($soso)
        {
            if(isset( $user->permission->public_services_id ) && $user->permission->public_services_id != "0")
            {
                $boo = false;
            }
        }
    }

    return $boo;
}

function is_one_public_services_root()
{
    $soso = false;

    $user = auth()->guard('admin')->user();

    if ($user->id == 1) {
        return false;
    }

    if(isset($user->id))
    {
        $lolo = explode(',', $user->permission->permission);

        foreach ($lolo as $koko)
        {
            if($koko == "publicServices")
            {
                $soso = true;
                break;
            }
        }

        if($soso)
        {
            if(isset( $user->permission->public_services_id ) && $user->permission->public_services_id != "0")
            {
               $data = \App\Models\PublicService::where("id",$user->permission->public_services_id)->first();

               if(isset($data->parent_id) && $data->parent_id == 0)
               {
                   return true;
               }
            }
        }
    }

    return false;
}

function get_one_public_services()
{
    $boo = 0;
    $soso = false;

    $user = auth()->guard('admin')->user();

    if(isset($user->id))
    {
        $lolo = explode(',', $user->permission->permission);

        foreach ($lolo as $koko)
        {
            if($koko == "publicServices")
            {
                $soso = true;
                break;
            }
        }

        if($soso)
        {
            if(isset( $user->permission->public_services_id ) && $user->permission->public_services_id != "0")
            {
                $boo = $user->permission->public_services_id;
            }
        }
    }

    return $boo;
}

function get_user_city_id()
{
    $i=0;
$user = auth()->guard('admin')->user();

if(isset($user->id))
{
    $i=$user->city_id;
}
return $i;
}

function is_jawhar_user()
{
    $user = auth()->guard('admin')->user();

    if ($user->id == 1) {
        return true;
    }
    return false;
}

function admin_assets($dir)
{
    return url('/admin_assets/assets/' . $dir);
}

function getLocal()
{
    return app()->getLocale();
}

function mainResponse($status, $message, $data, $code, $key,$validator){
    try {
        $result['status'] = $status;
        $result['code'] = $code;
        $result['message'] = $message;

        if ($validator && $validator->fails()) {
            $errors = $validator->errors();
            $errors = $errors->toArray();
            $message = '';
            foreach ($errors as $key => $value) {
                $message .= $value[0] . ',';
            }
            $result['message'] = $message;
            return response()->json($result, $code);
        }elseif (!is_null($data)) {


            if ($status) {
                if ($data != null && array_key_exists('data', $data)) {
                    $result[$key] = $data['data'];
                } else {
                    $result[$key] = $data;
                }
            } else {
                $result[$key] = $data;
            }
        }
        return response()->json($result, $code);
    } catch (Exception $ex) {
        return response()->json([
            'line' => $ex->getLine(),
            'message' => $ex->getMessage(),
            'getFile' => $ex->getFile(),
            'getTrace' => $ex->getTrace(),
            'getTraceAsString' => $ex->getTraceAsString(),
        ], $code);
    }
}

function convertAr2En($string){
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);
    $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);
    return $englishNumbersOnly;
}

function random_number($digits){
    return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
}

function type(){
    return [__('common.store'),__('common.product'),__('common.url')];
}

function position(){
    return [__('common.site'),__('common.mobile')];
}

function typeArrive(){


    return[
        '1'=>__('print.delivery'),
            '2'=>__('print.pickup'),
            '3'=>__('print.both')
        ];

}

function optionArrive(){


    return[

        '1'=>__('print.have_delivery_team'),
        '2'=>__('print.link_delivery_company'),
        '3'=>__('print.both')
    ];

}

function sendNotificationToUsers( $tokens, $message,$msgType="1",$target_id="1",$title="جوهر" ){
        try {
            $headers = [
                'Authorization: key='.env("FireBaseKey"),
                'Content-Type: application/json'
            ];

            if(!empty($tokens)) {

                $data = [
                    "registration_ids" => $tokens,
                    "data" => [
                        'body' => $message,
                        'type' => "notify",
                        'title' => $title,
                        'target_id' => $target_id,
                        'msgType' => $msgType,// 1=>msg , 2=>order
                        'badge' => 1,
                        "click_action" => 'FLUTTER_NOTIFICATION_CLICK',
                        'icon' => 'myicon',//Default Icon
                        'sound' => 'mySound'//Default sound
                    ],
                    "notification" => [
                        'body' => $message,
                        'type' => "notify",
                        'title' => $title,
                        'target_id' => $target_id,
                        'msgType' => $msgType,// 1=>msg , 2=>order
                        'badge' => 1,
                        "click_action" => 'FLUTTER_NOTIFICATION_CLICK',
                        'icon' => 'myicon',//Default Icon
                        'sound' => 'mySound'//Default sound
                    ]
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $result = curl_exec($ch);
                curl_close($ch);
               // $resultOfPushToIOS = "Done";
             //   return $result; // to check does the notification sent or not
            }
                    //   return $resultOfPushToIOS." ".$resultOfPushToAndroid;
        //    return $result;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }





    }

function slugURL($title){
    $WrongChar = array('@', '؟', '.', '!','?','&','%','$','#','{','}','(',')','"',':','>','<','/','|','{','^');

    $titleNoChr = str_replace($WrongChar, '', $title);
    $titleSEO = str_replace(' ', '-', $titleNoChr);
    return $titleSEO;
}

function pointInPolygon($point, $polygon) {
  //  pointOnVertex = true;

    // Transform string coordinates into arrays with x and y values
    $point = pointStringToCoordinates($point);
    $vertices = array();
    foreach ($polygon as $vertex) {
        $vertices[] = pointStringToCoordinates($vertex);
    }

    // Check if the point sits exactly on a vertex
    if (pointOnVertex($point, $vertices) == true) {
        return true;
    }

    // Check if the point is inside the polygon or on the boundary
    $intersections = 0;
    $vertices_count = count($vertices);

    for ($i=1; $i < $vertices_count; $i++) {
        $vertex1 = $vertices[$i-1];
        $vertex2 = $vertices[$i];
        if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
            return true;
        }
        if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
            $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
            if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                return true;
            }
            if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                $intersections++;
            }
        }
    }
    // If the number of edges we passed through is odd, then it's in the polygon.
    if ($intersections % 2 != 0) {
        return true;
    } else {
        return false;
    }
}

function pointOnVertex($point, $vertices) {
    foreach($vertices as $vertex) {
        if ($point == $vertex) {
            return true;
        }
    }

}

function pointStringToCoordinates($pointString) {
    $coordinates = explode(" ", $pointString);
    return array("x" => $coordinates[0], "y" => $coordinates[1]);
}

function getUsers($created_at , $id){

    $lolo = "<table style='overflow-wrap: break-word;'>
                    <tr style='border: 1px solid black'>
                        <th style='border: 1px solid black'>#</th>
                        <th style='border: 1px solid black'>الاسم</th>
                        <th style='border: 1px solid black'>المبلغ</th>
                         <th style='border: 1px solid black'>نوع العملية</th>
                        <th style='border: 1px solid black'>التفاصيل</th>
                     </tr>";

    $w_c = \App\Models\UserWallet::where("created_at",$created_at)->get();

    if( count($w_c) < 2 || count($w_c) > 4 )
    {
        return "لا يوجد";
    }
    $koko = 0;
    foreach ($w_c as $one)
    {
        if($one->user_id == 1)
        {
            $lolo .=" <tr style='border: 1px solid black'>
                    <td style='border: 1px solid black'>".(++$koko)."</td>
                    <td style='border: 1px solid black'>"."متجر جوهر"."</td>
                    <td style='border: 1px solid black'>".$one->total_price."</td>
                     <td style='border: 1px solid black'>".($one->type == 0 ? "دخل" :"خرج")."</td>
                    <td style='border: 1px solid black'>".$one->details."</td>
                  </tr>";
        }
        else
        {
            $lolo .=" <tr style='border: 1px solid black'>
                    <td style='border: 1px solid black'>".(++$koko)."</td>
                    <td style='border: 1px solid black'>".(isset($one->user->id) ? $one->user->name : "اسم مجهول")."</td>
                    <td style='border: 1px solid black'>".$one->total_price."</td>
                      <td style='border: 1px solid black'>".($one->type == 0 ? "دخل" :"خرج")."</td>
                    <td style='border: 1px solid black'>".$one->details."</td>
                  </tr>";
        }


    }

    $lolo .="</table>";

    return $lolo;

}

function getAshabGameInfo($gameId){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://as7abcard.com/api/v1/products/".$gameId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer ".getApiKey(),
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

function getAshabGames()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://as7abcard.com/api/v1/products");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer ".getApiKey(),
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    $all = json_decode($response);

    $data = $all->products;
//$lolo = [];
    foreach ($data as $key => $value) {

      $data_one = getAshabGameInfo($value->id) ;

        if (!isset($data_one->service) || empty($data_one->service)) {
            //unset($all->products->{$key});
            $all->products[$key]->service = "no service";
        }
        else
        {
//            $lolo[] = $data_one;
            $all->products[$key]->service = $data_one->service;
        }
    }
//dd($lolo);
//    dd($all->products);
    return $all;
}

function getApiKey()
{
     $api_key = Setting::where("id","1")->first()->api_key;

     return $api_key;
}

function getAshabOrderId($orderId){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://as7abcard.com/api/v1/order/?order_id=".$orderId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Authorization: Bearer ".getApiKey(),
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

function from_doller_to_turky($value)
{
        $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;
        return (double)number_format($exchange_rate * $value, 2, '.', '');
}
function getServicesName($num,$type)
{
    if($type == 0)
    {
        return getServicesName_Profit($num);
    }
    elseif ($type == 1)
    {
        return getServicesName_Expenses($num);
    }
    return "";
}
function getAllServicesName_Profit()
{
    return array('الخدمات العامة','الالعاب','المعاهد','شركات الاتصالات','خدمات اصحاب','المتاجر','منتجات الخدمة','بطاقات الشبكات','تجديد اشتراكات النت','تصفير محفظة التركي');
}

function getServicesName_Profit($num)
{
    return getAllServicesName_Profit()[$num];
}

function getAllServicesName_Expenses()
{
    return array('مصروف محال','فرق في تصريف العملة','تكاليف الخروج لمدينة','راتب موظف','سحب ارباح اللاعبين');
}

function getServicesName_Expenses($num)
{
   return getAllServicesName_Expenses()[$num];
}

function getAllAshabPages()
{
    return array('قائمة العاب','قائمة 2','قائمة 3');
}

function check_version_in_post(\Illuminate\Http\Request $request)
{
    $user_id = auth('api')->id();
    $tettings = Setting::orderByDesc('id')->first();
    $msg = isset($tettings->stop_text) && !empty($tettings->stop_text) ? $tettings->stop_text : "يجب تحديث التطبيق";

    if($tettings->version_status == 1)
    {
        if($request->has('version'))
        {
            if($tettings->version != $request->version)
            {
                $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
                sendNotificationToUsers($tokens, $msg, 1, 0);
                return $msg;
            }
        }
        else
        {
            return $msg;
        }
    }

    return "";
}

function  get_user_carrency_from_api()
{
    try
    {
        $from_api = auth('api')->user();

        if(isset($from_api->id))
        {
            if(isset($from_api->currency) && ($from_api->currency == "turkey" || $from_api->currency == "dollar"))
            {
                return $from_api->currency;
            }
            else
            {
                return "turkey";
            }
        }
        else
        {
            return "turkey";
        }
    }
    catch(\Exception $exception)
    {
        return "turkey";
    }
}


function currency()
{
    $lolo = session()->get("lolo");

    if(!is_null($lolo) && isset($lolo) && $lolo != "" && ($lolo == "dollar" || $lolo == "turkey"))
    {
        if($lolo == "dollar")
        {
            return "1";
        }
        elseif($lolo == "turkey")
        {
            return "";
        }
    }
    else
    {
        $aaa = get_user_carrency_from_api();

        if($aaa == "dollar")
        {
            return "1";
        }
        elseif($aaa == "turkey")
        {
            return "";
        }
    }

    return "";
}

function set_currency($obj)
{
    if(isset($obj->currency))
    {
        session()->put("lolo",$obj->currency);
    }
    else
    {
        session()->put("lolo","");
    }
}
function User_Wallet_Check_Balance($id , $price)
{

    $balanceIn = UserWallet::where('user_id', $id)->where('type', 0)->sum('total_price');
    $balanceOut = UserWallet::where('user_id', $id)->where('type', 1)->sum('total_price');
    $balance = $balanceIn - $balanceOut;
    if ($balance >= $price  )
    {
        $bool = true;
    }
    else
    {
        $bool  =false;
    }
    return $bool;
}


function set_currency_now($currency)
{
    if(isset($currency))
    {
        session()->put("lolo",$currency);
    }
    else
    {
        session()->put("lolo","");
    }
}

function get_currency_now()
{

    if(currency() == 1)
    {
        return "dollar";
    }
    else
    {
        return "turkey";
    }




}


function numbersappGetSections(){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://api.numbersapp.online/getSections?api_key=".env("numbersappApiKey"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}
function getServiceCountries($section){
    $apiKey =env("numbersappApiKey");
    $response= \Http::get("http://api.numbersapp.online/getServiceCountries?api_key=$apiKey&section=$section");
 return json_decode($response);

}
function getLiveNumber($section,$service){
    $apiKey =env("numbersappApiKey");
    $response= \Http::get("http://api.numbersapp.online/getLiveNumber?api_key=$apiKey&section=$section&service=$service");
 return json_decode($response);

}
