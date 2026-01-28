<x-action-section>
    <x-slot name="title">
        {{ __('Browser Sessions') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and log out your active sessions on other browsers and devices.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-slate-600">
            {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-5 space-y-4">
                <!-- Other Browser Sessions -->
                @foreach ($this->sessions as $session)
                    <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="flex-shrink-0 mt-0.5">
                            @if ($session->agent->isDesktop())
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="text-sm font-semibold text-slate-900">
                                {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} - {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                            </div>

                            <div class="mt-1">
                                <div class="text-xs text-slate-500">
                                    {{ $session->ip_address }}

                                    @if ($session->is_current_device)
                                        <span class="ml-2 px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-50 rounded-full">{{ __('This device') }}</span>
                                    @else
                                        <span class="ml-2">{{ __('Last active') }} {{ $session->last_active }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (! $session->is_current_device)
                            <button type="button" wire:click="deleteSession({{ $session->id }})" wire:loading.attr="disabled" class="px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-lg transition duration-200 disabled:opacity-50">
                                {{ __('Delete') }}
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5">
            <button type="button" wire:click="confirmLogout" wire:loading.attr="disabled" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50">
                {{ __('Log Out Other Browser Sessions') }}
            </button>

            <x-action-message class="ms-3" on="loggedOut">
                {{ __('Done.') }}
            </x-action-message>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">
                {{ __('Log Out Other Browser Sessions') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <input type="password" class="block w-3/4 px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition duration-200"
                                autocomplete="current-password"
                                placeholder="{{ __('Password') }}"
                                x-ref="password"
                                wire:model="password"
                                wire:keydown.enter="logoutOtherBrowserSessions" />

                    <x-input-error for="password" class="mt-2 text-sm text-red-600" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <button type="button" wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" class="px-6 py-2.5 bg-slate-200 text-slate-900 font-semibold rounded-lg hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition duration-200 disabled:opacity-50">
                    {{ __('Cancel') }}
                </button>

                <button type="button" class="ms-3 px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 disabled:opacity-50"
                            wire:click="logoutOtherBrowserSessions"
                            wire:loading.attr="disabled">
                    {{ __('Log Out Other Browser Sessions') }}
                </button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
