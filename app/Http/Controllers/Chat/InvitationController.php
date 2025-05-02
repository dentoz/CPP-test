<?php

namespace App\Http\Controllers\Chat;

use App\Events\UserInvited;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvitationRequest;
use App\Models\ChatRoom;
use App\Models\Invitation;
use App\Models\ChatRoomUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class InvitationController extends Controller
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function invite(InvitationRequest $request, $chatRoomId)
    {
        $validated = $request->validated();
        $toUser = User::where(['email' => $validated['email']])->first();

        DB::beginTransaction();
        try {
            $invitation = Invitation::create([
                'chat_room_id' => $chatRoomId,
                'from_user_id' => $request->user()->id,
                'to_user_id' => $toUser->id,
            ]);

            $chatRoom = ChatRoom::find($chatRoomId);

            if ($toUser->fcm_token) {
                $this->sendFCMNotification(
                    $toUser->fcm_token,
                    'Invitation to join chat room',
                    $request->user()->name . ' invited you to join a chat room',
                    [
                        'type' => 'INVITE_REQUESTED',
                        'invitation_id' => $invitation->id,
                        'chat_room_id' => $chatRoomId,
                        'from_user' => $request->user()->email,
                        'topic' => $chatRoom->name,
                    ]
                );
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'ok.',
                'error' => null,
                'data' => ['chat_room_id' => $chatRoomId]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function respond(Request $request, $invitationId)
    {
        $request->validate(['status' => 'required|in:accepted,rejected']);

        $invitation = Invitation::findOrFail($invitationId);
        $invitation->status = $request->status;
        $invitation->save();

        if ($request->status === 'accepted') {
            ChatRoomUser::firstOrCreate([
                'chat_room_id' => $invitation->chat_room_id,
                'user_id' => $invitation->to_user_id,
            ]);

            Http::post('http://localhost:3000/join', [
                'roomId' => "chat.room.{$invitation->chat_room_id}",
                'user' => $request->user()->email,
            ]);
        }

        $fromUser = User::find($invitation->from_user_id);

        if ($fromUser->fcm_token) {
            $this->sendFCMNotification(
                $fromUser->fcm_token,
                'Invitation ' . $request->status,
                $request->user()->name . " has {$request->status} your invitation",
                [
                    'type' => $request->status == 'accepted' ? 'INVITATION_ACCEPTED' : 'INVITATION_REJECTED',
                    'chat_room_id' => $invitation->chat_room_id,
                    'invitation_id' => $invitation->id,
                    'from_user' => $request->user()->email,
                    'status' => $request->status
                ]
            );
        }

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => ['chat_room_id' => $invitation->chat_room_id]
        ], 200);
    }

    private function sendFCMNotification($token, $title, $body, $data = [])
    {
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $this->messaging->send($message);
    }
}
