<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950">
        <div class="mx-auto flex max-w-5xl flex-col items-center px-6 py-12 sm:py-16">
            <div class="flex items-center gap-3 rounded-full bg-white/5 px-4 py-2 text-sm font-medium text-indigo-100 shadow-md ring-1 ring-white/10">
                <img src="{{ asset('storage/logo.png') }}" alt="Crafty logo" class="h-10 w-10 rounded-full object-contain shadow" />
                <div class="leading-tight">
                    <p class="text-[11px] uppercase tracking-[0.18em] text-indigo-200">Crafty</p>
                    <p class="text-sm text-indigo-50">Art Marketplace</p>
                </div>
            </div>

            <div class="mt-8 w-full max-w-3xl overflow-hidden rounded-2xl bg-white/95 p-6 shadow-2xl ring-1 ring-slate-200 backdrop-blur">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-500">Policy</p>
                    <img src="{{ asset('storage/logo.png') }}" alt="Crafty logo" class="hidden h-10 w-10 object-contain sm:block" />
                </div>

                <div class="prose prose-slate max-w-none">
                    {!! $policy !!}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
