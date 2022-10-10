<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // index page view
    public function IndexView(){

        $featureItemCount = Product::where('is_featured', 'Yes') -> where('status', 1) -> count();
        $featureProductArr = Product::where('is_featured', 'Yes') -> where('status', 1)  -> get() -> toArray();
        $featureItemChunk = \array_chunk($featureProductArr, 4);
        // dd($featureItemChunk);

        $newProudct = Product::orderBy('id', 'DESC') -> where('status', 1) -> limit(3) -> get();
        $new_product_arr = json_decode($newProudct);
        // echo '<pre>'; print_r($new_product_arr); die;
        
        $page_name = 'index';
        return view('frontend.index', compact('page_name', 'featureItemChunk', 'featureItemCount', 'new_product_arr'));
    }




}
