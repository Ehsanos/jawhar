<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class GameServies extends Model
{
    use SoftDeletes;
    protected $table = 'game_servies';
     protected $appends = ['new_price','nagma_price'];

    
    public function getNewPriceAttribute($value){
        return $this->api_id > 0 ? ($this->purchasing_price + (($this->purchasing_price * $this->commission)/100)) : $this->price;
    }
    
    /////nagma_games
        public function getNagmaPriceAttribute()
    {        $user_id = auth('api')->id();
    
            $checkNagmaGames = NagmaGame::where('user_id',$user_id)->where('nagma_game_ids', 'LIKE', '%,'.$this->id.',%')->first();

            $promo_code = PromoCode::where('target_type' , '3')->whereHas('users' , function ($q){
                $q->where('user_id' , auth('api')->id());
            })->whereHas('targets' , function($q2){
                $q2->where('target_id' , $this->id);
            })->first();
            
             if($promo_code){
              $jawharProfit = ($this->purchasing_price * $this->commission) / 100;
              $price = ($jawharProfit * $promo_code->percent) / 100 ;
              return $this->new_price - $price ;
           }else if($checkNagmaGames){
              $jawharProfit = ($this->purchasing_price * $this->commission)/ 100;
              $NagmaGames = $jawharProfit * $checkNagmaGames->average /100 ;
              return $this->new_price -$NagmaGames ;
           }else{
                return "0";
           }

    }

        //relatioship one to many
    public function game()
    {
        return $this->belongsTo(Game::class,'game_id')->withTrashed();
    }
    
    public function api()
    {
        return $this->belongsTo(Api::class,'api_id');
    }

    public function result($value)
    {
        if($this->is_dollar){
            $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

            return $exchange_rate * $value;
        }
        else{
            return $value;
        }

    }
    public function result1($value)
    {
        if($this->is_dollar){
            return $value;
        }
        else{
            $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

            return $value / $exchange_rate;
        }

    }
}
