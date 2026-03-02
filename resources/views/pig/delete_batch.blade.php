<div id="pig-delete-batch-modal" class="fixed inset-0 z-[90] hidden transition duration-300" aria-hidden="true">
    <button type="button" data-delete-batch-modal-close="1" class="absolute inset-0 z-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></button>

    <div class="absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-lg rounded-3xl border border-rose-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-700">Danger Zone</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Delete Batch</h2>
                    <p class="mt-2 text-sm text-slate-600">This will permanently remove the selected pig batch from records.</p>
                </div>
                <button type="button" data-delete-batch-modal-close="1" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-rose-700">Batch Information</p>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">Batch Code</dt>
                        <dd id="delete-batch-code" class="font-semibold text-slate-900">N/A</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">Batch Name</dt>
                        <dd id="delete-batch-name" class="font-semibold text-slate-900">Unknown Batch</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-slate-600">No. of Pigs</dt>
                        <dd id="delete-batch-count" class="font-semibold text-slate-900">0 pigs</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-900">
                Please confirm before deleting. This action cannot be undone.
            </div>

            <div class="mt-6 flex flex-wrap justify-end gap-2">
                <button type="button" data-delete-batch-modal-close="1" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </button>
                <button id="confirm-delete-batch-button" type="button" class="rounded-xl bg-rose-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2">
                    Delete Batch
                </button>
            </div>
        </div>
    </div>
</div>
