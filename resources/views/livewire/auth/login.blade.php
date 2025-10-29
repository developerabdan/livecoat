<div>
    <x-layouts.auth>
        <x-flash-messages />
        <form wire:submit.prevent="{{ $requires2fa ? 'verify2fa' : 'login' }}">
            @csrf
            {{-- 2FA Verification --}}
            @if($requires2fa)
                <div class="card w-full">
                    <header>
                        <h2>{{ __('Two-Factor Authentication') }}</h2>
                        <p>{{ __('Enter the code from your authenticator app') }}</p>
                    </header>
                    <section>
                        <div class="form grid gap-6">
                            {{-- Code Input Section --}}
                            <div class="grid gap-3">
                                <label for="totp-code" class="label font-semibold">{{ __('Enter 6-Digit Code') }}</label>
                                <input 
                                    class="input text-center text-lg font-mono tracking-widest" 
                                    id="totp-code" 
                                    type="text" 
                                    wire:model="totpCode"
                                    placeholder="000000"
                                    maxlength="6"
                                    pattern="[0-9]*"
                                    inputmode="numeric"
                                    autofocus
                                >
                                <p class="text-xs text-muted-foreground text-center">{{ __('Open your authenticator app to view your verification code') }}</p>
                            </div>
                        </div>
                    </section>
                    <footer class="flex flex-col items-center gap-2">
                        <button type="submit" class="btn w-full">{{ __('Verify Code') }}</button>
                        <button type="button" wire:click="cancelVerification" class="btn-ghost w-full">
                            {{ __('Cancel') }}
                        </button>
                    </footer>
                </div>
            @else
                {{-- Login Form --}}
                <div class="card w-full">
                <header>
                    <h2>Login to your account</h2>
                    <p>Enter your details below to login to your account</p>
                </header>
                <section>
                    <div class="form grid gap-6">
                        <div class="grid gap-2">
                            <label for="demo-card-form-email">Email</label>
                            <input type="email" id="demo-card-form-email" placeholder="Enter your email"
                                wire:model="email">
                            @error('email')
                                <p class="text-destructive text-sm">{{ $errors->first('email') }}</p>
                            @enderror
                        </div>
                        <div class="grid gap-2">
                            <div class="flex items-center gap-2">
                                <label for="demo-card-form-password">Password</label>
                                <a href="#"
                                    class="ml-auto inline-block text-sm underline-offset-4 hover:underline">Forgot
                                    your password?</a>
                            </div>
                            <input type="password" id="demo-card-form-password" placeholder="Enter your password"
                                wire:model="password">
                            @error('password')
                                <p class="text-destructive text-sm">{{ $errors->first('password') }}</p>
                            @enderror
                        </div>
                        
                        {{-- Google reCAPTCHA v2 --}}
                        @if(setting('google_recaptcha_v2.enabled'))
                            <div wire:ignore class="flex justify-center">
                                <div wire:ignore class="g-recaptcha" 
                                    data-sitekey="{{ setting('google_recaptcha_v2.site_key') }}"
                                    data-callback="onRecaptchaSuccess">
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
                <footer class="flex flex-col items-center gap-2">
                    <button type="submit" class="btn w-full">{{ __('Login') }}</button>
                    <p class="mt-4 text-center text-sm">Don't have an account? <a href="#"
                            class="underline-offset-4 hover:underline">Sign up</a></p>
                </footer>
            </div>
            @endif
        </form>
    </x-layouts.auth>
    
    {{-- Google reCAPTCHA Script --}}
    @if(setting('google_recaptcha_v2.enabled'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
            function onRecaptchaSuccess(token) {
                @this.set('recaptchaToken', token);
            }
            
            // Listen for reset event from Livewire
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('resetRecaptcha', () => {
                    if (typeof grecaptcha !== 'undefined') {
                        grecaptcha.reset();
                    }
                });
            });
        </script>
    @endif
</div>
