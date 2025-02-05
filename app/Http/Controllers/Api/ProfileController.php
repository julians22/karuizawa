<?php

namespace App\Http\Controllers\Api;

use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ProfileController extends Controller
{

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'required|min:10',
            'address' => 'sometimes|nullable|string',
            'is_male' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if (!$user->isCrew()) {

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this profile',
            ], 403);
        }

        if ($request->has('current_password')) {
            $passwordValidator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password',
            ]);

            if ($passwordValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $passwordValidator->errors(),
                ], 400);
            }

            if (!\Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 400);
            }

            $user->update([
                'password' => \Hash::make($request->new_password),
            ]);
        }

        $user->personalData()->updateOrCreate(
            ['user_id' => $request->user_id],
            [
                'phone' => $request->phone,
                'address' => $request->address ?? '',
                'is_male' => $request->is_male
            ]);


        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
        ]);
    }

}
