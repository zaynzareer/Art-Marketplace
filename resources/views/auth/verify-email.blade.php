<x-guest-layout>
    <div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-xl w-40%">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm p-8 sm:p-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('storage/logo.png') }}" alt="Art Marketplace Logo" class="h-10 w-10 rounded-xl" />
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Email verification</p>
                        <p class="text-base font-semibold text-slate-900">Check your inbox</p>
                    </div>
                </div>

                <div class="mb-6 text-sm text-slate-600">
                    Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl p-3">
                        A new verification link has been sent to the email address you provided in your profile settings.
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}" class="space-y-5">
                    @csrf

                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-slate-900 text-white px-4 py-3 font-semibold shadow-sm hover:translate-y-px transition">
                        Resend Verification Email
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-200 flex flex-wrap items-center justify-center gap-4 text-sm">
                    <a href="{{ route('profile.show') }}" class="font-semibold text-slate-600 hover:text-slate-900">
                        Edit Profile
                    </a>
                    <span class="text-slate-300">•</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-semibold text-slate-600 hover:text-slate-900">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
