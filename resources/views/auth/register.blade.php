<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="hidden lg:flex flex-col justify-between rounded-3xl border border-slate-200 bg-white shadow-sm p-10">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Art Marketplace</p>
                    <h1 class="text-3xl font-semibold text-slate-900 leading-snug">Join our community of artists and collectors.</h1>
                    <p class="text-slate-600">Create your account to start buying exclusive pieces or selling your artwork to a global audience.</p>
                </div>
                <img src="{{ asset('storage/img3.jpg') }}" alt="Art Marketplace" class="w-full rounded-2xl my-8" />
                <div class="grid grid-cols-2 gap-4 text-sm text-slate-700">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Easy seller setup</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Safe & secure payments</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">Global marketplace</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">24/7 support</div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-8 sm:p-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ Storage::url('logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Get started</p>
                        <p class="text-base font-semibold text-slate-900">Create your account</p>
                    </div>
                </div>

                <x-validation-errors class="mb-4" />

                @if ($errors->has('oauth'))
                    <div class="mb-4 text-sm text-red-600">{{ $errors->first('oauth') }}</div>
                @endif

                <a href="{{ route('auth.google.redirect') }}" class="w-full inline-flex justify-center items-center gap-2 rounded-xl border border-slate-200 bg-white text-slate-900 px-4 py-3 font-semibold shadow-sm hover:bg-slate-50 transition mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.52 13.3 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.57-.14-3.08-.39-4.5H24v9h12.65c-.55 2.95-2.23 5.45-4.73 7.12l7.22 5.6C43.82 38.06 46.5 31.77 46.5 24.5z"/><path fill="#FBBC05" d="M10.54 28.59a14.5 14.5 0 010-9.18l-7.98-6.19A24 24 0 000 24c0 3.93.94 7.64 2.56 10.78l7.98-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.9-5.78l-7.22-5.6c-2.01 1.35-4.6 2.13-8.68 2.13-6.26 0-11.48-3.8-13.46-9.04l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>
                    Sign up with Google
                </a>

                <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input id="name" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" :readonly="submitting" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="email" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" :readonly="submitting" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="age" class="block text-sm font-medium text-slate-700">Age</label>
                            <input id="age" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="number" name="age" value="{{ old('age') }}" required :readonly="submitting" />
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 ml-2">Role</label>
                            <select id="role" name="role" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" required>
                                <option value="">Select Role</option>
                                <option value="seller" @selected(old('role') === 'seller')>Seller</option>
                                <option value="buyer" @selected(old('role') === 'buyer')>Buyer</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                        <input id="city" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="city" value="{{ old('city') }}" required :readonly="submitting" />
                    </div>

                    <div>
                        <label for="street" class="block text-sm font-medium text-slate-700">Street Address</label>
                        <input id="street" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="street" value="{{ old('street') }}" required :readonly="submitting" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input id="password" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="password" name="password" required autocomplete="new-password" :readonly="submitting" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                        <input id="password_confirmation" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="password" name="password_confirmation" required autocomplete="new-password" :readonly="submitting" />
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="text-sm">
                            <label for="terms" class="flex items-start gap-2 text-slate-700">
                                <input type="checkbox" name="terms" id="terms" class="rounded border-slate-300 text-slate-900 focus:ring-slate-500 mt-0.5" required />
                                <span>
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="font-semibold text-slate-900 hover:underline">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="font-semibold text-slate-900 hover:underline">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0" :disabled="submitting">
                        <svg x-show="submitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!submitting">Create Account</span>
                        <span x-show="submitting">Creating Account...</span>
                    </button>

                    <p class="text-sm text-center text-slate-600">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Sign in</a></p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
