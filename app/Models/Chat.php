<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class Chat extends Model
{
    public function getChats(){
        $chats = DB::select("SELECT * FROM chat_users WHERE status = 1 ORDER BY  id DESC");
        return $chats;
    }
 
    public function getChatsForDatatable(){
        $chats = $this->getChats();
        return DataTables::of($chats)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                
                $url_user_name = str_replace(' ', '-', $row->name);

                $view_link = route('chat.view', $url_user_name); 
                $delete_link = route('chat.destroy', $row->id);
                $edit_link = '';
                return view('components.action-button', compact('edit_link','delete_link','view_link'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function isUserExist($userName){
        return DB::table('chat_users')
                 ->where('name', $userName)
                 ->where('status', 1)
                 ->exists();
    }
    

    public function getChatMessagesByUser($userName){
        $messages = DB::select("SELECT * FROM chat_messages WHERE user_id = ? ORDER BY id ASC", [$userName]);
        return $messages;
    }
    
    public function saveAdminChat($user_name, $message){
        $isChatSaved = DB::insert("INSERT INTO chat_messages (user_id, sender_type, message) 
                                     VALUES (?, ?, ?)", [$user_name, 'Admin', $message]);
        
        return $isChatSaved;
    }
    
    public function softDeleteChat($id){
        $updated = DB::table('chat_users')
                    ->where('id', $id)
                    ->where('status', 1)  // Ensure we only update active users
                    ->update(['status' => 0]);
        return $updated; 
    }




    public function sendNotification($fcmToken, $message, $deviceToken = null){
        try {
            // Your FCM Server Key
            $serverKey = 'AAAAPqxa1YI:APA91bGLQZIyruTPbvZPFKUFUwhlFdzelWTYFEUe1lIEU8ZcktucOohQK_65QvSmSgFPqcjffjMUaOYb5W0nsmVj45JHwDWB7SqfZKHWKnz6T7lmR4aYE60_CxtuMZp1VinO105B8IvM';

            // Firebase API URL
            $url = 'https://fcm.googleapis.com/fcm/send';

            // Notification payload
            if ($fcmToken) {
                $data = [
                    'to' => $fcmToken,
                    'notification' => [
                        'title' => 'Testfabrics Chat',
                        'body' => $message,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ],
                    'data' => [
                        'message' => $message
                    ]
                ];
            }

            // For iOS devices, add 'apns' key to the payload
            if ($deviceToken) {
                $data['apns'] = [
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => 'Testfabrics Chat',
                                'body' => $message
                            ],
                            'sound' => 'default'
                        ],
                        'data' => [
                            'message' => $message
                        ]
                    ]
                ];
            }

            // Headers
            $headers = [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ];

            // Make the HTTP POST request
            $response = Http::withHeaders($headers)->post($url, $data);

            // Check HTTP response code
            if ($response->status() !== 200) {
                throw new \Exception("Notification failed to send. HTTP code: {$response->status()}");
            }

            return $response->json(); // Return the response if needed
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }


}
