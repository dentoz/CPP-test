<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\User;

class UserController extends Controller
{
    public function availability(\App\Http\Requests\UserAvailabilityRequest $request)
    {
        $validated = $request->validated();
        $isAvailable = User::where('nick_name', $validated['nickname'])->exists();

        \DB::beginTransaction();
        try {
            if (!$isAvailable) {
            User::where('google_id', $validated['google_id'])->update([
                'nick_name' => $validated['nickname'],
            ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
            'status' => 500,
            'message' => 'An error occurred while processing your request.',
            'error' => $e->getMessage(),
            ], 500);
        }
        

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => [
                'nickname' => $validated['nickname'],
                'available' => $isAvailable,
            ],
        ], 200);
    }
}
