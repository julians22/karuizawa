<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerOtp;
use App\Services\CustomerAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly CustomerAuthService $authService)
    {
    }

    public function requestLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->authService->requestOtp($request->email, CustomerOtp::PURPOSE_LOGIN);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP login telah dikirim ke email Anda.']);
    }

    public function requestRegisterOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->authService->requestOtp($request->email, CustomerOtp::PURPOSE_REGISTER);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP registrasi telah dikirim ke email Anda.']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'full_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'is_male' => 'nullable|boolean',
        ]);

        try {
            $result = $this->authService->registerWithOtp(
                $request->email,
                $request->otp,
                $request->password,
                $request->full_name,
                $request->phone,
                $request->address,
                $request->is_male
            );
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil.',
            'data' => [
                'token' => $result['token'],
                'customer' => $result['customer'],
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'otp' => 'required|digits:6',
        ]);

        try {
            $result = $this->authService->loginWithPasswordAndOtp($request->email, $request->password, $request->otp);
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

    /**
     * Issue an API token with email + password only (no OTP). Used by the Kizoku
     * frontend login, which has no OTP step in its UX.
     */
    public function issueToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->authService->issueTokenWithPassword($request->email, $request->password);
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

    public function requestSetPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'full_name' => 'nullable|string',
        ]);

        try {
            $this->authService->requestOtp($request->email, CustomerOtp::PURPOSE_SET_PASSWORD, $request->full_name);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP set password telah dikirim ke email Anda.']);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'nullable|string',
        ]);

        try {
            $customer = $this->authService->setPasswordWithOtp(
                $request->email,
                $request->otp,
                $request->password,
                $request->full_name
            );
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil disimpan.',
            'data' => $customer,
        ]);
    }

    public function requestResetPasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->authService->requestOtp($request->email, CustomerOtp::PURPOSE_RESET_PASSWORD);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP reset password telah dikirim ke email Anda.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->authService->resetPasswordWithOtp($request->email, $request->otp, $request->password);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.',
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
