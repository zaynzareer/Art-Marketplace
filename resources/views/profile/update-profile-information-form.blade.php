<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Profile Information</h3>
            <p class="text-sm text-slate-600">Update your account's profile information and email address.</p>
        </div>
    </x-slot>

    <x-slot name="description">
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <label class="block text-sm font-medium text-slate-700">Profile Photo</label>

                <div class="mt-3 flex items-center gap-4">
                    <div class="relative">
                        <div class="size-20 rounded-2xl overflow-hidden border-2 border-slate-200" x-show="! photoPreview">
                            
                                <img src="{{ $this->user->profile_photo_url }}"
                                    alt="{{ $this->user->name }}"
                                    class="size-full object-cover">
                            
                        </div>
                        <div class="size-20 rounded-2xl overflow-hidden border-2 border-slate-200" x-show="photoPreview" style="display: none;">
                            <span class="block size-full bg-cover bg-no-repeat bg-center"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <button type="button" x-on:click.prevent="$refs.photo.click()" class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:translate-y-px transition">
                            Select New Photo
                        </button>
                        @if ($this->user->profile_photo_path)
                            <button type="button" wire:click="deleteProfilePhoto" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
                                Remove Photo
                            </button>
                        @endif
                    </div>
                </div>

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
            <input id="name" type="text" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-800">
                        Your email address is unverified.
                        <button type="button" class="font-semibold text-amber-900 hover:underline" wire:click.prevent="sendEmailVerification">
                            Click here to re-send the verification email.
                        </button>
                    </p>
                    @if ($this->verificationLinkSent)
                        <p class="mt-2 text-sm font-medium text-green-700">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-span-6 sm:col-span-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Age -->
            <div>
                <label for="age" class="block text-sm font-medium text-slate-700">Age</label>
                <input id="age" type="number" min="1" max="120" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" wire:model="state.age" required />
                <x-input-error for="age" class="mt-2" />
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                <input id="city" type="text" maxlength="30" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" wire:model="state.city" required autocomplete="address-level2" />
                <x-input-error for="city" class="mt-2" />
            </div>
        </div>

        <!-- Street -->
        <div class="col-span-6 sm:col-span-4">
            <label for="street" class="block text-sm font-medium text-slate-700">Street Address</label>
            <input id="street" type="text" maxlength="100" class="mt-2 block w-full rounded-xl border-slate-200 focus:border-slate-500 focus:ring-slate-500" wire:model="state.street" required autocomplete="street-address" />
            <x-input-error for="street" class="mt-2" />
        </div>

        <!-- Role (Read-only) -->
        <div class="col-span-6 sm:col-span-4">
            <label class="block text-sm font-medium text-slate-700">Account Type</label>
            <div class="mt-2 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="capitalize text-slate-900 font-semibold">
                    {{ ucfirst($this->user->role) }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-2">Account type cannot be changed after registration.</p>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            <span class="text-sm font-medium text-green-600">Saved successfully!</span>
        </x-action-message>

        <x-button>
            Save Changes    
        </x-button>
    </x-slot>
</x-form-section>
