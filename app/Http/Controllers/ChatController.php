<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request){
        if($request->ajax()){
            $chats = (new Chat)->getChatsForDatatable();
            return $chats;
        }
        return view('admin.chat.index');
    }
    
    public function view($id){
        $chatObj = new Chat;
        $user_name = str_replace('-', ' ', $id);
        if(!$chatObj->isUserExist($user_name))
            return redirect()->route('chat.index');
            
        
        $chats = $chatObj->getChatMessagesByUser($user_name);
        return view('admin.chat.view')->with(['chats' => $chats, 'user_name' => $user_name]);
    }

    public function saveAdminChat(Request $request){
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'user_name' => 'required'
            ]);
            // return $request;
            $chatObj = new Chat;
            $isSaved = $chatObj->saveAdminChat($request->user_name,$request->message);

            if ($isSaved) {
                // Get the user's information to send the notification using DB::select
                $userQuery = "SELECT * FROM chat_users WHERE name = ?";
                $user = DB::select($userQuery, [$request->user_name]);

                if (!empty($user)) {
                    $fcmToken = $user[0]->fcm_token;  // Assuming 'fcm_token' is a field in the chat_users table
                    $deviceToken = $user[0]->device_token;  // Assuming 'device_token' is a field in the chat_users table
    
                    // Send notification to the user
                    $chatObj->sendNotification($fcmToken, $request->message, $deviceToken);
                }
    
                // Redirect back to chat view
                return redirect()->route('chat.view')->with(['id' => $request->user_name]);
            } else {
                return redirect()->back()->with('error', 'Error saving the message');
            }


            return redirect()->route('chat.view')->with(['id' => $request->user_name]);
        
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

    }
    
    public function delete($id){
        try {
            $isDelete = (new Chat)->softDeleteChat($id);
            return redirect()->route('chat.index');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }


}
