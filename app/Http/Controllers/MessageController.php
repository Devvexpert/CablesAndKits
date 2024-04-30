<?php
/**
 * MessageController.php
 *
 * This file contains the MessageController class, which handles sending and fetching messages.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Message;
use App\Models\UserKey;
use App\Traits\EncryptDecrypt;

/**
 * Class MessageController
 * 
 * This controller handles sending and fetching messages.
 * 
 * @package App\Http\Controllers
 */
class MessageController extends Controller
{
    use EncryptDecrypt;

    /**
     * Handle sending a message.
     *
     * @param Request $request The HTTP request object containing the message data.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $message_type = $request->input('msgtype');
        $message = $request->input('message');
        
        // Validate the request
        $request->validate(
            [
            'recipient' => 'required|string',
            'message' => 'required|string',
            ]
        );
        
        
        $user = Auth::user()->with(
            ['keys' => function ($query) {
                $query->latest()->first();
            }]
        )->first();

        $key = $user['keys'][0]['encrypted_key'];
        $encrypted_key_id = $user['keys'][0]['id'];

        // Encrypt the message
        $encryptedMessage = $this->encryptString($message, $key);
        // Save the message to the database
        $message = new Message();
        $message->sender_id = Auth::id();
        $message->recipient_id = $request->input('recipient');
        $message->message = $encryptedMessage;
        $message->encrypted_key_id = $encrypted_key_id;
        $message->expiry_type = $message_type;
        $message->save();

        // Redirect back with success message or any other response
        return response()->json(['status' => 200]);
    }

    /**
     * Fetch messages for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchMessage() 
    {
        $authUserId = Auth::id();
        $messages = Message::with('user')
            ->where('recipient_id', '!=', $authUserId)
            ->get();

        if ($messages->isNotEmpty()) {
            foreach ($messages as $key => $message) {
                $userKey = UserKey::find($message->encrypted_key_id);
                $key = $userKey->encrypted_key;

                $decryptedMessage = $this->decryptString($message->message, $key);
                $formattedMessages[] = [
                    'message' => $decryptedMessage,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            }
            return response()->json(
                [
                'status' => 200,
                'messages' => $formattedMessages
                ]
            );
        } else {
            $formattedMessages[] = [
                'message' => '',
                'created_at' => '',
            ];
            return response()->json(
                [
                'status' => 500,
                'messages' => $formattedMessages
                ]
            );
        }
    }
}
