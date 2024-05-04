<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserWallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class UsersExport implements FromArray,  WithHeadings ,ShouldAutoSize ,WithStrictNullComparison
{
    use Exportable;
    public function array(): array
    {
        $user=User::where('type','0')->with('wallet')->get();

        foreach($user as $one){
            $items[] = [
                $one->id,
                $one->user_code,
                $one->name,
                $one->wallet->where('type',0)->sum('total_price') - $one->wallet->where('type',1)->sum('total_price'),                
                $one->email,
                $one->mobile .' ',
                $one->image_profile,
                $one->social_type,
                $one->status,
                $one->created_at,
            ];
        }

        return $items;
    }

    public function headings() :array
    {
        return ["id","الكود","الاسم","الرصيد","الايميل","الموبايل","الصورة","نوع الحساب","حالة الحساب","تاريخ الانشاء"];

    }
}



