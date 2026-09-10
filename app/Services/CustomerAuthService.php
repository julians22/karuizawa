<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerOtp;
use App\Notifications\CustomerOtpNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAuthService
{
    private const OTP_LENGTH = 6;
    private const OTP_TTL_MINUTES = 5;
    private const MAX_ATTEMPTS = 5;
    private const RESEND_COOLDOWN_SECONDS = 60;

    /**
     * Resolve (or create) the customer for the given email and send an OTP.
     */
    public function requestOtp(string $email, ?string $fullName = null): void
    {
        $email = Str::lower($email);

        $recentOtp = CustomerOtp::where('email', $email)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if ($recentOtp && $recentOtp->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            throw new \RuntimeException('Mohon tunggu sebelum meminta kode baru.');
        }

        $customer = $this->resolveExistingCustomer($email);

        $otp = (string) random_int(100000, 999999);

        CustomerOtp::create([
            'customer_id' => $customer?->id,
            'email' => $email,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        $recipient = $customer ?? new Customer(['full_name' => $fullName ?? 'Customer', 'email' => $email]);
        $recipient->notify(new CustomerOtpNotification($otp));
    }

    /**
     * Verify the OTP and return a customer with a fresh Sanctum token.
     *
     * @return array{customer: Customer, token: string}
     */
    public function verifyOtp(string $email, string $otp, ?string $fullName = null): array
    {
        $email = Str::lower($email);

        $customerOtp = CustomerOtp::where('email', $email)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if (!$customerOtp) {
            throw new \RuntimeException('Kode OTP tidak ditemukan, silakan minta kode baru.');
        }

        if ($customerOtp->isExpired()) {
            throw new \RuntimeException('Kode OTP sudah kedaluwarsa.');
        }

        if ($customerOtp->attempts >= self::MAX_ATTEMPTS) {
            throw new \RuntimeException('Percobaan verifikasi terlalu banyak, silakan minta kode baru.');
        }

        if (!Hash::check($otp, $customerOtp->otp_hash)) {
            $customerOtp->increment('attempts');
            throw new \RuntimeException('Kode OTP tidak valid.');
        }

        $customerOtp->update(['verified_at' => now()]);

        $customer = $this->resolveExistingCustomer($email);

        if (!$customer) {
            $customer = Customer::create([
                'full_name' => $fullName ?? 'Customer',
                'email' => $email,
                'is_male' => true,
            ]);
        }

        $customerOtp->update(['customer_id' => $customer->id]);

        $token = $customer->createToken('customer-api')->plainTextToken;

        return ['customer' => $customer, 'token' => $token];
    }

    /**
     * customers.email is unique, so this simply finds the matching record if any.
     */
    private function resolveExistingCustomer(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }
}
