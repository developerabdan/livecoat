<div>
    <x-layouts.auth>
        <x-flash-messages />
        <form wire:submit.prevent="login">
            {{-- TOTP Authentication - To be implemented --}}
            <div class="card w-full hidden">
                <header>
                    <h2>Two-Factor Authentication</h2>
                    <p>Scan the QR code or enter the code manually</p>
                </header>
                <section>
                    <div class="form grid gap-6">
                        {{-- QR Code Section --}}
                        <div class="flex justify-center p-6 bg-muted/30 rounded-lg border-2 border-dashed border-border">
                            <div class="max-w-[200px] w-full">
                                <img src="{{ asset('images/qrcode-example.svg') }}" alt="TOTP QR Code" class="w-full h-auto rounded-md shadow-sm">
                            </div>
                        </div>
                        
                        {{-- Secret Key Section --}}
                        <div class="bg-muted/50 rounded-lg p-4 border border-border">
                            <p class="text-xs text-muted-foreground mb-2 uppercase tracking-wide">Secret Key</p>
                            <code class="text-base font-mono font-semibold text-foreground select-all">1234-5678-91012</code>
                        </div>
                        
                        {{-- Code Input Section --}}
                        <div class="grid gap-3">
                            <label for="totp-code" class="label font-semibold">Enter 4-Digit Code</label>
                            <input 
                                class="input text-center text-lg font-mono tracking-widest" 
                                id="totp-code" 
                                type="text" 
                                placeholder="0000"
                                maxlength="4"
                                pattern="[0-9]*"
                                inputmode="numeric"
                            >
                            <p class="text-xs text-muted-foreground text-center">Enter the code from your authenticator app</p>
                        </div>
                        
                        {{-- Action Button --}}
                        <button type="button" class="btn w-full">Verify Code</button>
                    </div>
                </section>
            </div>
            <div class="card w-full">
                <header>
                    <h2>Login to your account</h2>
                    <p>Enter your details below to login to your account</p>
                </header>
                <section>
                    <div class="form grid gap-6">
                        @csrf
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
                    </div>
                </section>
                <footer class="flex flex-col items-center gap-2">
                    <button type="submit" class="btn w-full">{{ __('Login') }}</button>
                    <p class="mt-4 text-center text-sm">Don't have an account? <a href="#"
                            class="underline-offset-4 hover:underline">Sign up</a></p>
                </footer>
            </div>
        </form>
    </x-layouts.auth>
</div>
