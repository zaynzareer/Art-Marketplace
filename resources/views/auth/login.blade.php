<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="hidden lg:flex flex-col justify-between rounded-3xl border border-slate-200 bg-white shadow-sm p-10">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Art Marketplace</p>
                    <h1 class="text-3xl font-semibold text-slate-900 leading-snug">Welcome back, creators and collectors.</h1>
                    <p class="text-slate-600">Log in to keep buying the pieces you love, manage your orders, or update your shop lineup in minutes.</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm text-slate-700 mt-10">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Secure checkout & buyer protection</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Real-time order tracking</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Seller analytics & inventory</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Curated recommendations</div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-8 sm:p-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ Storage::url('logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Sign in</p>
                        <p class="text-base font-semibold text-slate-900">Buyer & Seller access</p>
                    </div>
                </div>

                <x-validation-errors class="mb-4" />

                @if ($errors->has('oauth'))
                    <div class="mb-4 text-sm text-red-600">{{ $errors->first('oauth') }}</div>
                @endif

                <a href="{{ route('auth.google.redirect') }}" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-slate-200 bg-white text-slate-900 px-4 py-3 font-semibold shadow-sm hover:bg-slate-50 transition mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.52 13.3 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.57-.14-3.08-.39-4.5H24v9h12.65c-.55 2.95-2.23 5.45-4.73 7.12l7.22 5.6C43.82 38.06 46.5 31.77 46.5 24.5z"/><path fill="#FBBC05" d="M10.54 28.59a14.5 14.5 0 010-9.18l-7.98-6.19A24 24 0 000 24c0 3.93.94 7.64 2.56 10.78l7.98-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.9-5.78l-7.22-5.6c-2.01 1.35-4.6 2.13-8.68 2.13-6.26 0-11.48-3.8-13.46-9.04l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>
                    Continue with Google
                </a>

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="email" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input id="password" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="password" name="password" required autocomplete="current-password" />
                        @if (Route::has('password.request'))
                            <div class="mt-2 text-right">
                                <a class="text-sm font-semibold text-slate-600 hover:text-slate-900" href="{{ route('password.request') }}">Forgot your password?</a>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="flex items-center gap-2 text-slate-700">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 focus:ring-slate-500" />
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition">
                        Log in
                    </button>

                    @if (Route::has('register'))
                        <p class="text-sm text-center text-slate-600">New here? <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:underline">Create an account</a></p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
