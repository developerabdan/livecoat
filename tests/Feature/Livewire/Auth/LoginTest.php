<?php

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Models\User;
use App\Services\Interfaces\Totp as TotpInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Start session for all tests
        $this->startSession();
        
        // Reset config settings for each test
        Config::set('app.settings.google_recaptcha_v2.enabled', false);
        Config::set('app.settings.enable_totp.enabled', false);
    }

    #[Test]
    public function component_renders_successfully()
    {
        Livewire::test(Login::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.auth.login');
    }

    #[Test]
    public function email_is_required_for_login()
    {
        Livewire::test(Login::class)
            ->set('email', '')
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email' => 'required']);
    }

    #[Test]
    public function email_must_be_valid_email_format()
    {
        Livewire::test(Login::class)
            ->set('email', 'invalid-email')
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors(['email' => 'email']);
    }

    #[Test]
    public function password_is_required_for_login()
    {
        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', '')
            ->call('login')
            ->assertHasErrors(['password' => 'required']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_email()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'wrong@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasNoErrors()
            ->assertNoRedirect()
            ->assertDispatched('resetRecaptcha');

        $this->assertGuest();
    }

        #[Test]
    public function user_cannot_login_with_invalid_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasNoErrors()
            ->assertNoRedirect()
            ->assertDispatched('resetRecaptcha');

        $this->assertGuest();
    }

        #[Test]
    public function login_shows_2fa_form_when_user_has_totp_enabled()
    {
        Config::set('app.settings.enable_totp.enabled', true);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'totp_secret' => 'secret123',
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertSet('requires2fa', true)
            ->assertSet('pendingUserId', $user->id)
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function user_cannot_login_with_invalid_2fa_code()
    {
        Config::set('app.settings.enable_totp.enabled', true);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'totp_secret' => 'secret123',
        ]);

        // Mock the TOTP service
        $totpMock = Mockery::mock(TotpInterface::class);
        $totpMock->shouldReceive('verifyCode')
            ->with('secret123', '000000')
            ->andReturn(false);
        
        $this->app->instance(TotpInterface::class, $totpMock);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->set('totpCode', '000000')
            ->call('verify2fa')
            ->assertSet('totpCode', '')
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function verify_2fa_requires_verification_code()
    {
        Livewire::test(Login::class)
            ->set('requires2fa', true)
            ->set('pendingUserId', '123')
            ->set('totpCode', '')
            ->call('verify2fa')
            ->assertNoRedirect();
    }

        #[Test]
    public function verify_2fa_fails_without_pending_user_id()
    {
        Livewire::test(Login::class)
            ->set('totpCode', '123456')
            ->set('pendingUserId', null)
            ->call('verify2fa')
            ->assertSet('requires2fa', false)
            ->assertSet('pendingUserId', null)
            ->assertSet('totpCode', '');
    }

        #[Test]
    public function verify_2fa_fails_when_user_not_found()
    {
        Livewire::test(Login::class)
            ->set('totpCode', '123456')
            ->set('pendingUserId', '999')
            ->set('requires2fa', true)
            ->call('verify2fa')
            ->assertSet('requires2fa', false)
            ->assertSet('pendingUserId', null)
            ->assertSet('totpCode', '');
    }

        #[Test]
    public function verify_2fa_fails_when_user_has_no_totp_secret()
    {
        $user = User::factory()->create([
            'totp_secret' => null,
        ]);

        Livewire::test(Login::class)
            ->set('totpCode', '123456')
            ->set('pendingUserId', $user->id)
            ->set('requires2fa', true)
            ->call('verify2fa')
            ->assertSet('requires2fa', false)
            ->assertSet('pendingUserId', null)
            ->assertSet('totpCode', '');
    }

        #[Test]
    public function user_can_cancel_2fa_verification()
    {
        Livewire::test(Login::class)
            ->set('email', 'user@example.com')
            ->set('password', 'password123')
            ->set('requires2fa', true)
            ->set('pendingUserId', '123')
            ->set('totpCode', '123456')
            ->call('cancelVerification')
            ->assertSet('requires2fa', false)
            ->assertSet('pendingUserId', null)
            ->assertSet('totpCode', '')
            ->assertSet('email', 'test@example.com')  // Reset to default value
            ->assertSet('password', 'password')        // Reset to default value
            ->assertDispatched('resetRecaptcha');
    }

        #[Test]
    public function recaptcha_token_is_reset_after_cancel_verification()
    {
        Livewire::test(Login::class)
            ->set('recaptchaToken', 'test-token')
            ->call('cancelVerification')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha');
    }

        #[Test]
    public function login_fails_when_recaptcha_is_enabled_but_token_is_empty()
    {
        Config::set('app.settings.google_recaptcha_v2.enabled', true);

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('recaptchaToken', '')
            ->call('login')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha')
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function login_fails_when_recaptcha_verification_fails()
    {
        Config::set('app.settings.google_recaptcha_v2.enabled', true);
        Config::set('app.settings.google_recaptcha_v2.secret_key', 'test-secret-key');

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
            ], 200),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('recaptchaToken', 'invalid-token')
            ->call('login')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha')
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function login_fails_when_recaptcha_api_throws_exception()
    {
        Config::set('app.settings.google_recaptcha_v2.enabled', true);
        Config::set('app.settings.google_recaptcha_v2.secret_key', 'test-secret-key');

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => function () {
                throw new \Exception('Network error');
            },
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('recaptchaToken', 'test-token')
            ->call('login')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha')
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function recaptcha_is_skipped_when_secret_key_is_not_configured()
    {
        Config::set('app.settings.google_recaptcha_v2.enabled', true);
        Config::set('app.settings.google_recaptcha_v2.secret_key', null);

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password123')
            ->set('recaptchaToken', 'test-token')
            ->call('login')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha')
            ->assertNoRedirect();

        $this->assertGuest();
    }

        #[Test]
    public function recaptcha_token_is_reset_after_failed_login_attempt()
    {
        Config::set('app.settings.google_recaptcha_v2.enabled', false);

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->set('recaptchaToken', 'test-token')
            ->call('login')
            ->assertSet('recaptchaToken', '')
            ->assertDispatched('resetRecaptcha');
    }

    #[Test]
    public function component_dispatches_reset_recaptcha_event_on_failed_login()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertDispatched('resetRecaptcha');
    }

        #[Test]
    public function component_has_correct_default_values()
    {
        Livewire::test(Login::class)
            ->assertSet('email', 'test@example.com')
            ->assertSet('password', 'password')
            ->assertSet('totpCode', '')
            ->assertSet('requires2fa', false)
            ->assertSet('pendingUserId', null)
            ->assertSet('recaptchaToken', '');
    }

        #[Test]
    public function component_properties_can_be_set()
    {
        Livewire::test(Login::class)
            ->set('email', 'newemail@example.com')
            ->assertSet('email', 'newemail@example.com')
            ->set('password', 'newpassword')
            ->assertSet('password', 'newpassword')
            ->set('totpCode', '654321')
            ->assertSet('totpCode', '654321');
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
