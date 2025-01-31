<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // authenticate user
    public function authUser(Request $request){
        
        try{
            $credentials =  $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
    
            if(Auth::attempt($credentials)){
                $request->session()->regenerate(); // Prevent session fixation attacks
                return redirect()->route("verification-history.index");
            }
    
            return redirect()->back()->with(['error',"Failed to authenticate "]);
        }
        catch(Exception $err){
            return redirect()->back()->with(['error' => $err->getMessage()]);
        }

    }

    public function login(){

        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route("verification-history.index");
        }
        
        return view('admin.auth.login');
    }
    // logout user 
    public function logout(Request $request){
        Auth::logout(); // Log the user out
    
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Prevent CSRF attacks
    
        return redirect()->route('login'); // Redirect to login page
    }

    public function profile(){
        $user = Auth::user();
        return view('admin.profile.index',['user' => $user]);
    }


    public function changePassword(Request $request){
        try {
            $request->validate([
                // 'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);
            $user = Auth::user();
            
            $hash = Hash::make($request->new_password);
            DB::update("UPDATE users SET password = ? WHERE id = ?", [
                $hash,
                $user->id
            ]);
            return redirect()->back()->with('success','Password updated successfully');

        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                'username' => 'required|string',
                'email' => 'required|email',
                'imgfile' => 'nullable|mimes:jpg|max:2048',
            ]);
            $user = Auth::user();
            DB::update("UPDATE users SET username = ?, email = ? WHERE id = ?",[
                $request->username,
                $request->email,
                $user->id
            ]);

            // upload img if exist
            if($request->file('imgfile')){
                $img = $request->file('imgfile');
                $folderName = 'profile';
                // get the extension and generate the filename
                $extension = $img->getClientOriginalExtension();
                $fileName = 'user.' . $extension;
                // Define the upload path
                $uploadPath = public_path($folderName);
                // Ensure the folder exists
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                // move the img
                $img->move($uploadPath, $fileName);
            }
            return redirect()->route('profile')->with('success',"Profile updated successfully");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
    }

}
