<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <label for="current_password" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('Current Password') }}
            </label>
            <input id="current_password" type="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition duration-200" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('New Password') }}
            </label>
            <input id="password" type="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition duration-200" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">
                {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" type="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition duration-200" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2 text-sm text-red-600" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200">
            {{ __('Save') }}
        </button>
    </x-slot>
</x-form-section>
