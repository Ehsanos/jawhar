<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserAddresse;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        // return new User([
        //    'name'     => $row[0],
        //    'email'    => $row[1], 
        //    'mobile'    => $row[2], 
        //    'password' => Hash::make($row[3]),
        //    'avatar'    => $row[4], 
        //    'gender'    => $row[5], 
        //    'birthdate'    => $row[6], 
        //    'city_id'    => $row[7], 
        //    'country_id'    => $row[8], 
        //    'remember_token'    => $row[9], 
        //    'status'    => $row[10], 
           
        // ]);

        $user= new User();
        $user->name     = $row[0];
        $user->email   = $row[1]; 
        $user->mobile    = $row[2]; 
        $user->password =Hash::make($row[3]);
        $user->avatar   = $row[4];
        $user->gender   = $row[5];
        $user->birthdate   = $row[6];
        $user->city_id    = $row[7];
        $user->country_id    =$row[8]; 
        $user->remember_token    = $row[9];
        $user->status   = $row[10];
           
        $user->save();

        $adress=new UserAddresse();

        $adress->user_id= $user->id;
        $adress->city_id= $row[11];
        $adress->area= $row[12];
        $adress->mobile= $row[13];
        $adress->addres = $row[14];
        $adress->save();
        return;
    }
}
