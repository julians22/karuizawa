<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerOtp;
use App\Notifications\CustomerOtpNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerAuthService
{
    private const OTP_TTL_MINUTES = 5;
    private const MAX_ATTEMPTS = 5;
    private const RESEND_COOLDOWN_SECONDS = 60;

    public function requestOtp(string $email, string $purpose, ?string $fullName = null): void
    {
        $email = Str::lower($email);

        if (!in_array($purpose, [
            CustomerOtp::PURPOSE_LOGIN,
            CustomerOtp::PURPOSE_REGISTER,
            CustomerOtp::PURPOSE_RESET_PASSWORD,
            CustomerOtp::PURPOSE_SET_PASSWORD,
        ], true)) {
            throw new \RuntimeException('Tujuan OTP tidak valid.');
        }

        $recentOtp = CustomerOtp::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest('id')
            ->first();

        if ($recentOtp && $recentOtp->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            throw new \RuntimeException('Mohon tunggu sebelum meminta kode baru.');
        }

        $customer = $this->resolveExistingCustomer($email);

        if ($purpose === CustomerOtp::PURPOSE_REGISTER && $customer) {
            throw new \RuntimeException('Email sudah terdaftar. Silakan login atau reset password.');
        }

        if ($purpose !== CustomerOtp::PURPOSE_REGISTER && !$customer) {
            throw new \RuntimeException('Customer tidak ditemukan.');
        }

        if ($purpose === CustomerOtp::PURPOSE_LOGIN && $customer && !$customer->password) {
            throw new \RuntimeException('Password belum dibuat. Gunakan set password terlebih dahulu.');
        }

        $otp = (string) random_int(100000, 999999);

        CustomerOtp::create([
            'customer_id' => $customer?->id,
            'email' => $email,
            'purpose' => $purpose,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
        ]);

        $recipient = $customer ?? new Customer(['full_name' => $fullName ?? 'Customer', 'email' => $email]);
        $recipient->notify(new CustomerOtpNotification($otp));
    }

    /**
     * @return array{customer: Customer, token: string}
     */
    public function loginWithPasswordAndOtp(string $email, string $password, string $otp): array
    {
        $email = Str::lower($email);
        $customer = $this->resolveExistingCustomer($email);

        if (!$customer || !$customer->password) {
            throw new \RuntimeException('Email atau password tidak valid.');
        }

        if (!Hash::check($password, $customer->password)) {
            throw new \RuntimeException('Email atau password tidak valid.');
        }

        $customerOtp = $this->verifyOtpForPurpose($email, $otp, CustomerOtp::PURPOSE_LOGIN);
        $customerOtp->update(['customer_id' => $customer->id]);

        $token = $customer->createToken('customer-api')->plainTextToken;

        return ['customer' => $customer, 'token' => $token];
    }

    public function setPasswordWithOtp(string $email, string $otp, string $password, ?string $fullName = null): Customer
    {
        $email = Str::lower($email);
        $customerOtp = $this->verifyOtpForPurpose($email, $otp, CustomerOtp::PURPOSE_SET_PASSWORD);

        $customer = $this->resolveExistingCustomer($email);

        if (!$customer) {
            $customer = Customer::create([
                'full_name' => $fullName ?? 'Customer',
                'email' => $email,
                'password' => Hash::make($password),
                'is_male' => true,
            ]);
        } else {
            $customer->update(['password' => Hash::make($password)]);
        }

        $customerOtp->update(['customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * @return array{customer: Customer, token: string}
     */
    public function registerWithOtp(
        string $email,
        string $otp,
        string $password,
        string $fullName,
        ?string $phone = null,
        ?string $address = null,
        ?bool $isMale = null
    ): array {
        $email = Str::lower($email);
        $customerOtp = $this->verifyOtpForPurpose($email, $otp, CustomerOtp::PURPOSE_REGISTER);

        if ($this->resolveExistingCustomer($email)) {
            throw new \RuntimeException('Email sudah terdaftar. Silakan login atau reset password.');
        }

        $customer = Customer::create([
            'full_name' => $fullName,
            'email' => $email,
            'password' => Hash::make($password),
            'phone' => $phone,
            'address' => $address,
            'is_male' => $isMale ?? true,
        ]);

        $customerOtp->update(['customer_id' => $customer->id]);

        return [
            'customer' => $customer,
            'token' => $customer->createToken('customer-api')->plainTextToken,
        ];
    }

    public function resetPasswordWithOtp(string $email, string $otp, string $password): void
    {
        $email = Str::lower($email);
        $customer = $this->resolveExistingCustomer($email);

        if (!$customer) {
            throw new \RuntimeException('Customer tidak ditemukan.');
        }

        $this->verifyOtpForPurpose($email, $otp, CustomerOtp::PURPOSE_RESET_PASSWORD);

        $customer->update(['password' => Hash::make($password)]);
        $customer->tokens()->delete();
    }

    private function verifyOtpForPurpose(string $email, string $otp, string $purpose): CustomerOtp
    {
        $customerOtp = CustomerOtp::where('email', $email)
            ->where('purpose', $purpose)
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

        return $customerOtp;
    }

    private function resolveExistingCustomer(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }
}
