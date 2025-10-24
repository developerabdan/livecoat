<x-layouts.auth title="Login">
    <div class="flex justify-center mb-6">
        <img src="{{ asset('logo.png') }}" alt="Logo" class="h-48 w-auto">
    </div>

    <div class="card w-full">
        <header>
            <h2>Login to your account</h2>
            <p>Enter your details below to login to your account</p>
        </header>
        <section>
            <form class="form grid gap-6">
                <div class="grid gap-2">
                    <label for="demo-card-form-email">Email</label>
                    <input type="email" id="demo-card-form-email" placeholder="Enter your email">
                </div>
                <div class="grid gap-2">
                    <div class="flex items-center gap-2">
                        <label for="demo-card-form-password">Password</label>
                        <a href="#" class="ml-auto inline-block text-sm underline-offset-4 hover:underline">Forgot
                            your password?</a>
                    </div>
                    <input type="password" id="demo-card-form-password" placeholder="Enter your password">
                </div>
            </form>
        </section>
        <footer class="flex flex-col items-center gap-2">
            <button type="button" class="btn w-full">Login</button>
            <p class="mt-4 text-center text-sm">Don't have an account? <a href="#"
                    class="underline-offset-4 hover:underline">Sign up</a></p>
        </footer>
    </div>
</x-layouts.auth>
