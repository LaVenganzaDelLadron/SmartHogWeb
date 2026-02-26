@php
    $showDeletePenModal = request('modal') === 'delete-pen';
    $deletePenCode = request('pen_id');
    $deletePenAction = is_string($deletePenCode) && trim($deletePenCode) !== ''
        ? route('web.pens.delete', ['penCode' => $deletePenCode])
        : route('show.pig');
@endphp

<div id="pig-delete-pen-modal" class="{{ $showDeletePenModal ? 'fixed inset-0 z-[90] opacity-100' : 'hidden' }} transition duration-300" aria-hidden="{{ $showDeletePenModal ? 'false' : 'true' }}">
    <a href="{{ route('show.pig') }}" class="absolute inset-0 z-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-lg rounded-3xl border border-rose-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Remove Pen</h2>
                    <p class="mt-2 text-sm text-slate-600">This action will permanently remove this pen from records.</p>
                </div>
                <a href="{{ route('show.pig') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-rose-700">Pen Information</p>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">Pen ID</dt>
                        <dd class="font-semibold text-slate-900">{{ request('pen_id', 'N/A') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">Pen Name</dt>
                        <dd class="font-semibold text-slate-900">{{ request('pen_name', 'Unknown Pen') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">Capacity</dt>
                        <dd class="font-semibold text-slate-900">{{ request('capacity', 0) }} pigs</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-900">
                Please confirm before deleting. This cannot be undone.
            </div>

            <form method="POST" action="{{ $deletePenAction }}" class="mt-6 flex flex-wrap justify-end gap-2">
                @csrf
                @method('DELETE')

                <a href="{{ route('show.pig') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-rose-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2">
                    Delete Pen
                </button>
            </form>
        </div>
    </div>
</div>
