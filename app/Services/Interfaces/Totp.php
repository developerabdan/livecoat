<?php

namespace App\Services\Interfaces;

interface Totp
{
    public function generateSecret(): string;
    public function decryptSecret(?string $encryptedSecret): ?string;
    public function getQrUrl(string $email, string $encryptedSecret, string $appName = 'MyApp'): string;
    public function getInlineQr(string $email, string $encryptedSecret, string $appName = 'MyApp'): string;
    public function verifyCode(string $encryptedSecret, string $otp): bool;
    public function throttle(string $cacheKey, int $maxAttempts = 5, int $decaySeconds = 60): bool;
}
