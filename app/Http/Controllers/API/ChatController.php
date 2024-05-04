<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Setting;
use App\Models\Chat;
use Validator;

use Carbon\Carbon;
use Image;
use DB;


class ChatController extends Controller
{


    public function sendChatMsgToAdmin(Request $request){
       
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        
        $chat = new Chat();
        $chat->user_id = $user_id;

        if(auth('api')->user()->type == 0){
            $chat->type = 'user';
        }


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = str_random(15) . "_" . rand(1000000, 9999999) . "_" . time() . "_" . rand(1000000, 9999999) . ".jpg";
            Image::make($file)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/chats/images/$image");
            $chat->image = $image;
        }


        if ($request->hasFile('file')) {
        $file = $request->file;
        $extension = $file->getClientOriginalExtension();
        $filename  = "pro_".Auth::user()->id."_".time()."_".rand(1,50000). '.' .$extension;
        $destinationPath = 'uploads/chats/files';
        $file->move($destinationPath,$filename);
        $chat->file = $filename;
        }


        $chat->message = $request->message;
        $chat->sender = 0;
        $chat->last_used = now();
        $chat->save();
        if($chat){
            Chat::where(['user_id'=>$user_id])->update(['last_used' => now()]);
            $newData = ['status' => true, 'code' => 200, 'message' => __('api.ok'),'chatMessage'=>$chat];
            return response()->json($newData);
        }
        $newData = ['status' => false, 'code' => 200, 'message' => __('api.Whoops')];
        return response()->json($newData);
    }



    public function myChatMsgWithAdmin(Request $request)
    {
        $mychat = Chat::where(['user_id' => auth('api')->user()->id])->get();

        return response()->json(['status' => true, 'code' => 200, 'message' => __('api.ok'), 'mychat' => $mychat ]);

    }



}