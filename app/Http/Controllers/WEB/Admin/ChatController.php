<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notify;
use Illuminate\Http\Request;
use Auth;

use App\Models\Chat;
use App\Models\User;
use App\Models\Token;
use App\Models\Language;
use App\Models\Setting;

class ChatController extends Controller
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



    public function chat_all_user(Request $request)
    {
        if (can('chat')) {
         
             if ($request->has('userName')) {
                 $allUsers = User::where('name', 'like', '%' . $request->get('userName') . '%')->pluck('id')->toArray();
               $users = Chat::whereIn('user_id',$allUsers)->orderBy('last_used', 'desc')->groupBy('user_id')->with('user')->get();
        }
        else{
                       $users = Chat::orderBy('last_used', 'desc')->groupBy('user_id')->with('user')->get();
 
        }

            foreach ($users as $one){
                $unread_count = Chat::where(['user_id' => $one->user_id, 'read' => 0, 'sender' => 0])->count();
                $one->unread_count = $unread_count;
            }

            return view('admin.chatting.users', ['users' => $users]);
        }
    }


    public function new_message(Request $request, $user_id)
    {
        if (can('chat')) {
            $chat_one = Chat::where('user_id', $user_id)->first();
                 $user = User::findOrFail($user_id);
            $all_chat_this_user='';
            if ($chat_one) {
                Chat::query()->where('user_id', $user_id)->where('sender',0)->update(['read' => 1]);
                $all_chat_this_user = Chat::where('user_id', $user_id)->get();
            }
            return view('admin.chatting.message', ['chat_one' => $chat_one, 'all_chat_this_user' => $all_chat_this_user, 'chat_user' => $user_id ,'user'=>$user]);
        }
    }


    public function new_message_admin(Request $request)
    {
        if (can('chat')) {
            $chat = New Chat;
            $chat->user_id = $request->user_id;
            $chat->message = $request->response;
            $chat->sender = 1;
            $chat->read = 0;
            $chat->save();

            $message =  "لديك رسالة جديدة";

            $notification = new Notify();
            $notification->user_id = $request->user_id;
            $notification->messag_type = 1;
            $notification->message = $message;
            $notification->save();

        $tokens = Token::where('user_id',$request->user_id)->pluck('fcm_token')->toArray();
        // return $tokens_ios;
        sendNotificationToUsers( $tokens, $message,1,$chat->id );


            return back()->with('status', __('cp.success'));
        }
    }

}