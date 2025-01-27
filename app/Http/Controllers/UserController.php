<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function authUser(Request $request){
        
        try{
            $credentials =  $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
    
            if(Auth::attempt($credentials)){
                return redirect()->route('service.index');
            }
    
            return response()->json(['error' => "getting error"]);
        }
        catch(Exception $err){
            return response()->json(['error' => $err]);
        }



        return back()
        ->withErrors(['error' => "Invalid Credentials"])
        ->withInput($request->except('password'));

    }




    public function login(){
        return view('admin.auth.login');
    }

    public function profile(){
        $user = Auth::user();
        return view('admin.profile.index',['user' => $user]);
    }

}
