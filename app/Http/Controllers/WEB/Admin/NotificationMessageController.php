<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Illuminate\Support\Facades\Validator;
use App\Notifications\NewPostNotification;

use App\Models\User;
use App\Models\Notification;
use App\Models\Notify;
use App\Models\Token;


class NotificationMessageController extends Controller
{


    public function index(Request $request)
    {
        $items = Notify::where('user_id',0)->orderBy('id', 'Desc')->get();
        return view('admin.notifications.home', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        $users = User::where('status','active')->get();
        return view('admin.notifications.create' );
    }

    public function store(Request $request)
    {
        $messageN = $request->message;

            $tokens_ios = Token::query()->pluck('fcm_token')->toArray();
            sendNotificationToUsers([], $tokens_ios, $messageN);

          $title = "متجر جوهر";
          $this->fcmPush($title ,$messageN);
                 // return $messageN;

      
        $notifications= New Notify;
       // $notifications->title = $request->title;
     $notifications->message = $request->message;
        $notifications->save();

        return redirect()->back()->with('status', __('cp.create'));
    }

    public function destroy($id)
    {
        $notifications = Notify::query()->findOrFail($id);
        if ($notifications->delete()) {
            return 'success';
        }
        return 'fail';
    }

    function fcmPush($title ,$message)

{ 
//return $type[0];
    
    try {
        $headers = [
           'Authorization: key='.env("FireBaseKey"),
            'Content-Type: application/json'
        ];
        $notification= [
            "to"=> '/topics/jawhar',
            "notification"=>[
                'type' => "notify",
                'title' => $title,
                'body' => $message,
                'icon' => 'myicon',//Default Icon
                'sound' => 'mySound'//Default sound
            ]
        ];
       // return $notification;
      // return json_encode($data);
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        
        
        
        $result = curl_exec($ch);
        curl_close($ch);
        //return json_decode($result, true);
      //  return back()->with('success','Edit SuccessFully');
    } catch (\Exception $ex) {
     //   return $ex->getMessage();
}
}

   

}
