<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full rounded-3xl border border-slate-200 bg-white shadow-sm p-8 sm:p-10">
            <div class="flex items-center gap-3 mb-6">
                <img src="{{ Storage::url('logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">Complete Setup</p>
                    <p class="text-base font-semibold text-slate-900">Select Your Role</p>
                </div>
            </div>

            <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-sm text-slate-700">
                    Welcome, <span class="font-semibold text-slate-900">{{ $oauthUser['name'] }}</span>! Please complete your profile setup.
                </p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('oauth.store-with-role') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">Select Your Role</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Seller Card -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="seller" class="sr-only peer" required />
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-slate-900 peer-checked:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">Seller</p>
                                        <p class="text-sm text-slate-600">Sell your artwork</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Buyer Card -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="buyer" class="sr-only peer" required />
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-slate-900 peer-checked:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">Buyer</p>
                                        <p class="text-sm text-slate-600">Browse and buy artwork</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="age" class="block text-sm font-medium text-slate-700">Age</label>
                        <input id="age" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="number" name="age" :value="old('age')" required min="13" max="150" />
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                        <input id="city" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="city" :value="old('city')" required maxlength="30" />
                    </div>
                </div>

                <div>
                    <label for="street" class="block text-sm font-medium text-slate-700">Street Address</label>
                    <input id="street" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" type="text" name="street" :value="old('street')" required maxlength="100" />
                </div>

                <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition">
                    Complete Setup
                </button>

                <p class="text-sm text-center text-slate-600">
                    You cannot change your role after this.
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
