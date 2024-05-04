<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Language;
use App\Models\NetWork;
use App\Models\NetworksCards;
use Illuminate\Support\Str;

class CardImport implements ToModel 
{
    
     public function  __construct($network)
{
    $this->network = $network;
   
}
 

    /**
     * @param array $row
     *
     * @return Product|null
     */
    public function model(array $row)
    {
        $locales = Language::all()->pluck('lang');
        // dd($this->network->id);
        $check = NetworksCards::where(['pin'=>$row[0] , 'network_id'=>@$this->network->id])->first();    
        if(!$check){
            $card= new NetworksCards();
            $card->pin   = $row[0]; 
            $card->password    = $row[1]; 
            $card->network_id   = @$this->network->id;
            $card->wifi_id   = @$this->network->wifi_id;
            $card->save();
        }
       
        return;
    }
}
