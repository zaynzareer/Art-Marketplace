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
                    <img src="{{ asset('storage/logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Get started</p>
                        <p class="text-base font-semibold text-slate-900">Create your account</p>
                    </div>
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                        <input id="name" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input id="email" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="age" class="block text-sm font-medium text-slate-700">Age</label>
                            <input id="age" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="number" name="age" :value="old('age')" required />
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 ml-2">Role</label>
                            <select id="role" name="role" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" required>
                                <option value="">Select Role</option>
                                <option value="seller">Seller</option>
                                <option value="buyer">Buyer</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                        <input id="city" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="city" :value="old('city')" required />
                    </div>

                    <div>
                        <label for="street" class="block text-sm font-medium text-slate-700">Street Address</label>
                        <input id="street" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="street" :value="old('street')" required />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <input id="password" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                        <input id="password_confirmation" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="password" name="password_confirmation" required autocomplete="new-password" />
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

                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition">
                        Create Account
                    </button>

                    <p class="text-sm text-center text-slate-600">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Sign in</a></p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
