<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
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
