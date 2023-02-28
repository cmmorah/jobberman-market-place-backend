<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Utility\StringUtility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            "responseCode" => "00",
            "responseMessage" => "Success"
        ]);
    }

    public function read(){
        $products = Product::all();
        return response()->json([
            "responseCode" => "00",
            "responseMessage" => "Success",
            "data" => $products
        ]);
    }

    public function readById(Request $request){
        $validator = Validator::make($request -> all(),[
            'product_id' => 'required|string'
        ]);
        if ($validator -> failed()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $products = Product::find($request->product_id);
        if (!$products)
            return response()->json([
                "responseCode" => "25",
                "responseMessage" => "Unable to locate record"
            ]);
        return response()->json([
            "responseCode" => "00",
            "responseMessage" => "Success",
            "data" => $products
        ]);
    }

    public function delete(Request $request){
        $validator = Validator::make($request -> all(),[
            'product_id' => 'required|string'
        ]);
        if ($validator -> failed()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $products = Product::find($request->product_id);
        if (!$products)
            return response()->json([
                "responseCode" => "25",
                "responseMessage" => "Unable to delete record"
            ]);
        $products->delete();
        return response()->json([
            "responseCode" => "00",
            "responseMessage" => "Success"
        ]);
    }

    public function readByUserId(Request $request){
        $user = Auth::user();
        //$products = Product::whereRaw('user_id = ?',$request->user_id)->get();
        $products = Product::where('user_id','=',$user->id)->get();
        return response()->json([
            "responseCode" => "00",
            "responseMessage" => "Success",
            "data" => $products
        ]);
    }

    public function create(Request $request){
        //$stringUtility = new StringUtility();
        $validator = Validator::make($request -> all(),[
            'category' => 'required|string',
            'user_id' => 'required',
            'location' => 'required|string',
            'pictures' => 'required|string',
            'youtube_link' => 'required|string',
            'title' => 'required|string',
            'brand' => 'required|string',
            'condition' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
            'seller_phone' => 'required|digits:10',
            'seller_name' => 'required|string'
        ]);
        if ($validator -> fails()){
            return response()->json([
                'responseCode' => '106',
                'responseMessage' => StringUtility::formatValidatorMessage($validator->messages()->toJson())
            ]);
        }
        $createProduct = Product::create([
            'category' => $request->category,
            'user_id' => $request->user_id,
            'location' => $request->location,
            'pictures' => $request->pictures,
            'youtube_link' => $request->youtube_link,
            'title' => $request->title,
            'brand' => $request->brand,
            'condition' => $request->condition,
            'description' => $request->description,
            'price' => $request->price,
            'seller_phone' => $request->seller_phone,
            'seller_name' => $request->seller_name,
        ]);
        if (!$createProduct){
            return response()->json([
                'responseCode' => '88',
                'responseMessage' => 'Failed to create record'
            ]);
        }
        return response()->json([
            'responseCode' => '00',
            'responseMessage' => 'Success'
        ]);
    }
}
