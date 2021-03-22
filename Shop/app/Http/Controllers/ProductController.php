<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //Add New Record
    public function add(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'desc' => 'required',
            'image' => 'required|image',
            'price' => 'required',
        ]);
        if($validator->fails())
        {
            return redirect('post/create')
           ->withErrors($validator)
           ->withInput();
        }
        $p= new Product();
        $p->name=$request->name;
        $p->category=$request->category;
        $p->brand=$request->brand;
        $p->desc=$request->desc;
        $p->price=$request->price;
        $p->save();
        // storing image
        $url="http://localhost:8000/storage";
        $file= $request->file(key:'image');
        $extension=$file->getClientOriginalExtension();
        $path = $request->file(key: 'image')->storeAs(path: 'proimages/', name: $p->id.'.'.$extension);
        $p->image=$path;
        $p->imagepath=$url.$path;
        $p->save();

    }

    // Update Record
    public function update(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'name' => 'required',
            'category' => 'required',
            'brand' => 'required',
            'desc' => 'required',
            'id' => 'required',
            'price' => 'required',
        ]);
        if($validator->fails())
        {
            return redirect('post/create')
           ->withErrors($validator)
           ->withInput();
        }
        $p= Product::find($request->id);
        $p->name=$request->name;
        $p->category=$request->category;
        $p->brand=$request->brand;
        $p->desc=$request->desc;
        $p->price=$request->price;
        $p->save();
       return response()->json(['message'=>'Product Updated Successfully']);

    }

    // Delete Record
    public function delete(Request $request)
    {
        $validator= Validator::make($request->all(),[
            
            'id' => 'required',
            
        ]);
        if($validator->fails())
        {
            return redirect('post/create')
           ->withErrors($validator)
           ->withInput();
        }
        $p= Product::find($request->id)->delete();
        
       return response()->json(['message'=>'Product Deleted Successfully']);

    }

    // Display Record
    public function show(Request $request)
    {
        session(['keys'=>$request->keys]);
        $products=Products::where(function($q){
            $q->where('products.id','LIKE','%'.session(key: 'keys').'%')
            ->orwhere('products.name','LIKE','%'.session(key: 'keys').'%')
            ->orwhere('products.price','LIKE','%'.session(key: 'keys').'%')
            ->orwhere('products.category','LIKE','%'.session(key: 'keys').'%')
            ->orwhere('products.brand','LIKE','%'.session(key: 'keys').'%')
        })->select('products.*')->get();
        return response()->json(['products'=>$products]);
    }
}
