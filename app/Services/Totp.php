<?php

namespace App\Services;

use \BaconQrCode\Renderer\ImageRenderer;
use App\Services\Interfaces\Totp as TotpInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class Totp implements TotpInterface
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key (encrypted before storing).
     */
    public function generateSecret(): string
    {
        $secret = $this->google2fa->generateSecretKey();
        return Crypt::encryptString($secret);
    }

    /**
     * Decrypt stored secret before usage.
     */
    public function decryptSecret(?string $encryptedSecret): ?string
    {
        if (!$encryptedSecret) {
            return null;
        }
        return Crypt::decryptString($encryptedSecret);
    }

    /**
     * Generate provisioning URI for QR Code.
     */
    public function getQrUrl(string $email, string $encryptedSecret, string $appName = 'MyApp'): string
    {
        $secret = $this->decryptSecret($encryptedSecret);

        return $this->google2fa->getQRCodeUrl(
            $appName,  // issuer / company
            $email,
            $secret
        );
    }

    /**
     * Generate inline QR image (base64) for blade.
     */
    public function getInlineQr(string $email, string $encryptedSecret, string $appName = 'MyApp'): string
    {
        $qrUrl = $this->getQrUrl($email, $encryptedSecret, $appName);
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
            new \BaconQrCode\Renderer\Image\ImagickImageBackEnd()
        );

        $writer = new \BaconQrCode\Writer($renderer);
        return 'data:image/png;base64,' . base64_encode($writer->writeString($qrUrl));
    }

    /**
     * Verify input token from user.
     */
    public function verifyCode(string $encryptedSecret, string $otp): bool
    {
        $secret = $this->decryptSecret($encryptedSecret);
        return $this->google2fa->verifyKey($secret, $otp);
    }

    /**
     * Temporary throttle: avoid brute force
     */
    public function throttle(string $cacheKey, int $maxAttempts = 5, int $decaySeconds = 60): bool
    {
        $key = 'totp_attempts_' . $cacheKey;
        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return false;
        }

        Cache::put($key, $attempts + 1, $decaySeconds);
        return true;
    }
}
