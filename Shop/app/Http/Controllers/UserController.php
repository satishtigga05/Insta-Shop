<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Add New Record
    public function register(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|unique:users',
            
        ]);
        if($validator->fails())
        {
            return redirect('post/create')
           ->withErrors($validator)
           ->withInput();
        }
        $p= new User();
        $p->name=$request->name;
        $p->email=$request->email;
        $p->password=$request->password;
        
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
