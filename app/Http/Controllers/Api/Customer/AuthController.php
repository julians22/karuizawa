<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\CustomerAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly CustomerAuthService $authService)
    {
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'full_name' => 'nullable|string',
        ]);

        try {
            $this->authService->requestOtp($request->email, $request->full_name);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 429);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP telah dikirim ke email Anda.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'full_name' => 'nullable|string',
        ]);

        try {
            $result = $this->authService->verifyOtp($request->email, $request->otp, $request->full_name);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $result['token'],
                'customer' => $result['customer'],
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['success' => true, 'data' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out.']);
    }
}
