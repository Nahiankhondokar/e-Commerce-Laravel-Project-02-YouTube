<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CreateSection;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Image;

class ProductController extends Controller
{

    // Product view
    public function ProductView () {

        $product = Product::with(['getCategory' => function($query){
            $query -> select('id', 'category_name');
        }, 'getSection']) -> get();

        // $data = json_decode(json_encode($product));
        // echo "<pre>"; print_r($data);
        return view('backend.product.product_view', compact('product'));

    }


    
    // Product active or inactive status
    public function ProductActiveInactive(Request $request){

        $status_data = Product::find($request -> product_id);

        if($status_data -> status == 1){
            $update = Product::find($request -> product_id);
            $update -> status = 0;
            $update -> update();
            return 'inactive';

        }else {
            $update = Product::find($request -> product_id);
            $update -> status = 1;
            $update -> update();
            return 'active';
        }

    }

    // product add 
    public function ProductAddOrEdit(Request $request, $id=null){
 
        
        // indevidual title
        if($id){
            $allData['title'] = 'Edit Product';
            $allData['edit_product'] = Product::find($id);
            $product = Product::find($id);
            $message = "Product Updated Successfully";
        }else {
            $allData['title'] = 'Add Product';
            $allData['edit_product'] = '';
            $product = new Product();
            $message = "Product Inserted Successfully";
        }

        // Product add or update
        if($request -> isMethod('post')){
            // dd($request -> all());

            // validation
            $request -> validate([
                'product_name'      => 'required|regex:/^[\pL\s\-]+$/',
                'product_code'      => 'required|regex:/^[a-zA-Z0-9_-]*$/',
                'category_id'       => 'required',
                'product_price'     => 'required|numeric',
                'product_weight'    => 'required|regex:/^[0-9]*$/'
            ], [
                'product_name.required'         => 'Product Name is required',
                'category_id.required'          => 'Category is required',
                'product_weight.required'       => 'Product Weight is not correct',
            ]);


            // img upload 
            if($request -> hasFile('main_image')){

                $img = $request -> file('main_image');
                $unique = md5(time() . rand()) . '.' . $img -> getClientOriginalExtension();
                $img -> move(public_path('media/backend/product/large'), $unique);
                // Image::make($unique)->save('media/backend/product/large'); // w:1080 h:1200
                // Image::make($unique)->resize(520, 600)->save('media/backend/product/medium');
                // Image::make($unique)->resize(260, 300)->save('media/backend/product/small');

                @unlink('media/backend/product/large/'.$request -> old_img);

            }else {
                $unique = $request -> old_img;
            }

            // video upload 
            if($request -> hasFile('product_video')){

                $img = $request -> file('product_video');
                $video_name = md5(time() . rand()) . '.' . $img -> getClientOriginalExtension();
                $img -> move(public_path('media/backend/product/videos'), $video_name);

                @unlink('media/backend/product/videos/'.$request -> old_video);

            }else {
                $video_name = $request -> old_video;
            }

            // product store
            $cat_details = Category::find($request -> category_id);
            $product -> product_name    = $request -> product_name;
            $product -> category_id     = $request -> category_id;
            $product -> section_id      = $cat_details -> section_id;
            $product -> product_code    = $request -> product_code;
            $product -> product_color   = $request -> product_color;
            $product -> product_price   = $request -> product_price;
            $product -> product_discount = $request -> product_discount;
            $product -> product_weight  = $request -> product_weight;
            $product -> product_video   = $video_name ?? '';
            $product -> main_image      = $unique ?? '';
            $product -> description     = $request -> description;
            $product -> wash_care       = $request -> wash_care;
            $product -> fabric          = $request -> fabric;
            $product -> pattern         = $request -> pattern;
            $product -> sleeve          = $request -> sleeve;
            $product -> fit             = $request -> fit;
            $product -> occassion       = $request -> occassion;
            $product -> meta_title      = $request -> meta_title;
            $product -> meta_desc       = $request -> meta_desc;
            $product -> meta_keyword    = $request -> meta_keyword;
            $product -> is_featured     = $request -> is_featured ?? 'No';
            $product -> status          = 1;
            $product -> save();

            // msg
            $notify = [
                'message'       => $message,
                'alert-type'    => "success"
            ];

            return redirect() -> route('product.view') -> with($notify);


        }

    
       
        // get all sesction
        $allData['section'] = CreateSection::with('getCategory') -> get();
        // $data = json_decode(json_encode($section));
        // echo "<pre>"; print_r($data);


        // filter Arrays
        $allData['fabricArr']   = ['Cotton', 'Colyster', 'Wool'];
        $allData['sleeveArr']   = ['Full Sleeve', 'Half Sleeve', 'Short Sleeve'];
        $allData['patternArr']  = ['Cehcked', 'Plain', 'Solid', 'Printed'];
        $allData['fitArr']      = ['Regular', 'Slim'];
        $allData['ocassionArr'] = ['Casual', 'Formal'];



        return view('backend.product.product_add_edit', $allData);

    }


    // product main image delete by ajax
    public function ProductImageVideoDeleteAjax(Request $request){
        
       if($request -> text == 'image'){

            $product = Product::find($request -> product_id);

            @unlink('media/backend/product/large/'.$product -> main_image);

            $product -> main_image = '';
            $product -> update();
            return 'image';

       }else {

            $product = Product::find($request -> product_id);

            @unlink('media/backend/product/videos/'.$product -> product_video);

            $product -> product_video = '';
            $product -> update();
            return 'video';

       }

    }



    // product attribute add
    public function ProductAttrViewOrAdd($id, Request $request){
        // dd($request -> all());
        if($request -> isMethod('get')){

            $edit_product = Product::with('getProductAttr') -> find($id);
            return view('backend.product.product_attr_add', compact('edit_product'));

        }else {
            // get 4 Array item
            $allData = $request -> all();
            // echo"<pre>"; print_r($allData); die;

            // all data insetting by looping
            foreach($allData['sku'] as $key => $item){


                // validation checking
                $prevSKU = ProductAttribute::where('sku', $item) -> count();
                $prevSize = ProductAttribute::where('product_id', $id) -> where('size', $allData['size'][$key]) -> count();

                // product attribute create
                if($prevSKU > 0){
                    // msg
                    $notify = [
                        'message'       => 'This SKU already exists !',
                        'alert-type'    => 'warning'
                    ];

                    return redirect() -> back() -> with($notify);
                }elseif($prevSize > 0){
                    // msg
                    $notify = [
                        'message'       => 'This Size already exists !',
                        'alert-type'    => 'warning'
                    ];

                    return redirect() -> back() -> with($notify);
                }elseif(!empty($item)){

                    ProductAttribute::create([
                        'product_id'        => $id,
                        'size'              => $allData['size'][$key],
                        'stock'             => $allData['stock'][$key],
                        'price'             => $allData['price'][$key],
                        'sku'               => $item
                    ]);
                    
                }else {
                    // msg
                    $notify = [
                        'message'       => 'All fields are require !',
                        'alert-type'    => 'error'
                    ];

                    return redirect() -> back() -> with($notify);
                }

                    

            }

            // msg
            $notify = [
                'message'       => 'Product Attribute Add Successful',
                'alert-type'    => "info"
            ];

            return redirect() -> back() -> with($notify);


        }
        

    }


    // product attribuet update
    public function ProductAttrUpdate(Request $request){
        // dd($request -> all());
        $allData = $request -> all();

        foreach($allData['attrId'] as $key => $value){

            $update = ProductAttribute::find($value);
            $update -> stock = $allData['stock'][$key];
            $update -> price = $allData['price'][$key];
            $update -> update();

        }


         // msg
         $notify = [
            'message'       => 'Product Attribute Updated',
            'alert-type'    => "success"
        ];

        return redirect() -> back() -> with($notify);

    
    }


    // Product attribute active or inactive status
    public function ProductAttrActiveInactive(Request $request){

        $status_data = ProductAttribute::find($request -> product_attr);

        if($status_data -> status == 1){
            $update = ProductAttribute::find($request -> product_attr);
            $update -> status = 0;
            $update -> update();
            return 'inactive';

        }else {
            $update = ProductAttribute::find($request -> product_attr);
            $update -> status = 1;
            $update -> update();
            return 'active';
        }

    }


    // product attribuet delete
    public function ProductAttrDelete($id){

        $productAttr = ProductAttribute::find($id);
        $productAttr -> delete();

         // msg
        $notify = [
            'message'       => 'Product Attribute Deleted',
            'alert-type'    => "info"
        ];

        return redirect() -> back() -> with($notify);
    
    }


}
