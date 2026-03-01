@php
    $showAddFeedModal = request('modal') === 'add-growth';
@endphp

<!-- Add Feed Modal -->
<div id="growth-stage-modal" class="{{ $showAddFeedModal ? 'fixed inset-0 z-[70] opacity-100' : 'hidden' }} transition duration-200" aria-hidden="{{ $showAddFeedModal ? 'false' : 'true' }}">
    <!-- Overlay -->
    <a href="{{ route('show.feeding') }}" class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm" aria-label="Close modal"></a>

    <!-- Modal content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="pointer-events-auto w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-xl sm:p-6">
            <!-- Header -->
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 id="add-feed-title" class="text-lg font-semibold text-slate-900">Add Feed</h2>
                    <p class="mt-1 text-sm text-slate-500">Enter the name of the new feed.</p>
                </div>
                <a href="{{ route('show.feeding') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <!-- Feedback -->
            <form id="add-growth-form" method="POST" action="{{ route('web.feeding.addGrowth') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="growth_name" class="mb-1.5 block text-sm font-medium text-slate-700">Growth Stage Name <span class="text-rose-600">*</span></label>
                    <input
                        type="text"
                        name="growth_name"
                        id="growth_name"
                        placeholder="Enter growth stage name"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                        required
                    >
                </div>

                <div class="flex justify-end gap-2 pt-1">
                    <a href="{{ route('show.feeding') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>