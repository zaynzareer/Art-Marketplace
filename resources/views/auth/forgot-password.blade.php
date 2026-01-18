<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="hidden lg:flex flex-col justify-between rounded-3xl border border-slate-200 bg-white shadow-sm p-10">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Art Marketplace</p>
                    <h1 class="text-3xl font-semibold text-slate-900 leading-snug">Reset your password securely.</h1>
                    <p class="text-slate-600">We'll send you a password reset link to your email address. Just follow the instructions to create a new password.</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-8 sm:p-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('storage/logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Password recovery</p>
                        <p class="text-base font-semibold text-slate-900">Reset your password</p>
                    </div>
                </div>

                <div class="mb-6 text-sm text-slate-600">
                    Forgot your password? No problem. Just enter your email address and we'll send you a password reset link.
                </div>

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="email" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition">
                        Email Password Reset Link
                    </button>

                    <p class="text-sm text-center text-slate-600">Remember your password? <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Back to login</a></p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
