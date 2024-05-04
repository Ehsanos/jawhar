<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Chat extends Model
{
    
    use SoftDeletes;
    public $table = 'chats';
    protected $hidden  = ['updated_at', 'deleted_at'];
    protected $appends = ['user_name', 'date', 'time', 'admin_image'];
    protected $casts = ['user_id' => 'string', 'sender' => 'string', 'read' => 'string'];
    

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }


    public function getUserNameAttribute()
    {
      return User::query()->where('id',$this->user_id)->pluck('name')->first();
    }


    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function getTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('h:i:a');
    }


    public  function getImageAttribute($value){
        if($value){
            return url('uploads/chats/images/' . $value);
        }
    }
    

    public function  getFileAttribute($value){
        if($value){
            return url('uploads/chats/files/' . $value);
        }
    }


    public function getMessageAttribute($value)
    {
        if($this->type==1){
            return url('uploads/chats/' . $value);

        }else{
        return (string)$value;
        }

    }


    public function getAdminImageAttribute()
    {
        if($this->sender == 1){
            return url('/admin_assets/admin.png');
        }else{
            return null;
        }
    }



}
