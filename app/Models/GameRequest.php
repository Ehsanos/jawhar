<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GameRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'game_request';
    protected $fillable = [ 'mobile'];
    protected $hidden = ['updated_at'];

       public function game()
    {
        return $this->belongsTo(Game::class,'game_id')->withTrashed();
    }
       public function servies()
    {
        return $this->belongsTo(GameServies::class,'servies_id')->withTrashed();
    }
       public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
        public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    
    public function api(){
        return $this->belongsTo(Api::class);
    }
   
      }
  
        