<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-indigo-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15a7.488 7.488 0 00-5.982 3.725M9 9a3 3 0 106 0 3 3 0 00-6 0m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h2 class="font-bold text-xl text-slate-900 leading-tight">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">{{ __('Manage your account settings and preferences') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        @livewire('profile.update-profile-information-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        @livewire('profile.update-password-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                @endif

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="rounded-3xl border border-red-200 bg-white shadow-sm overflow-hidden">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
