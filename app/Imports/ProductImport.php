<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductCatecory;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Language;
use Illuminate\Support\Str;

class ProductImport implements ToModel ,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Product|null
     */
    public function model(array $row)
    {
        $locales = Language::all()->pluck('lang');
        $product= new Product(); 
        $product->price = $row['price'] ?? null;
        $product->store_id = $row['store'] ?? 0;
        $product->category_id =$row['category_id'] ?? null;
        $product->subCategory_id =$row['subcategory_id'] ?? null;
        $product->is_dollar =$row['is_dollar'] ?? null; // 0=No, 1=yes
        $product->count =$row['count'] ?? null;
        $product->available =0;
      
        $product->image=$row['image'] ?? null;
        
        foreach ($locales as $locale)
        {
            $product->translateOrNew($locale)->name = $row['name_'. $locale] ?? null;
            $product->translateOrNew($locale)->description = $row['description_'. $locale] ?? null;
        }

        $product->save();
    
        if($row['extra_images'] != null) {
            $images= explode(',',$row['extra_images']);
            foreach($images as $id => $item){
                    $items[] = [
                        'product_id' => $product->id,
                        'product_img' => $item,   
                    ];       
           
            }
            ProductImage::insert($items);
            }                
               
        return;
    }
}
