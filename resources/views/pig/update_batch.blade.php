<div id="pig-update-batch-modal" class="fixed inset-0 z-[95] hidden transition duration-300" aria-hidden="true">
    <button type="button" data-update-batch-modal-close="1" class="absolute inset-0 z-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></button>

    <div class="absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-3xl rounded-3xl border border-amber-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Batch Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Update Pig Batch</h2>
                    <p class="mt-2 text-sm text-slate-600">Review current details, update values, and save the latest batch information.</p>
                </div>
                <button type="button" data-update-batch-modal-close="1" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <div class="mt-5 grid gap-4 rounded-2xl border border-amber-100 bg-amber-50/70 p-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Batch Code</p>
                    <p id="update-batch-code-preview" class="mt-1 text-sm font-semibold text-amber-900">N/A</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Growth Stage</p>
                    <p id="update-batch-stage-preview" class="mt-1 text-sm font-semibold text-amber-900">N/A</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Assigned Pen</p>
                    <p id="update-batch-pen-preview" class="mt-1 text-sm font-semibold text-amber-900">N/A</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">Recorded Pigs</p>
                    <p id="update-batch-count-preview" class="mt-1 text-sm font-semibold text-amber-900">0 pigs</p>
                </div>
            </div>

            <div id="update-batch-feedback" class="mt-4 hidden rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700"></div>

            <form id="pig-update-batch-form" class="mt-6 space-y-5">
                @csrf

                <input type="hidden" id="update-batch-code" name="batch_code" value="">
                <input type="hidden" id="update-batch-growth-stage-code" name="growth_stage_code" value="">
                <input type="hidden" id="update-batch-pen-code" name="pen_code" value="">

                <div>
                    <label for="update-batch-name" class="mb-2 block text-sm font-medium text-slate-700">Batch Name <span class="text-rose-600">*</span></label>
                    <input id="update-batch-name" name="batch_name" type="text" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="update-batch-pig-count" class="mb-2 block text-sm font-medium text-slate-700">No. of Pigs <span class="text-rose-600">*</span></label>
                        <input id="update-batch-pig-count" name="pig_count" type="number" min="1" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                    </div>
                    <div>
                        <label for="update-batch-current-age" class="mb-2 block text-sm font-medium text-slate-700">Current Age (Days) <span class="text-rose-600">*</span></label>
                        <input id="update-batch-current-age" name="current_age_days" type="number" min="0" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                    </div>
                    <div>
                        <label for="update-batch-stage" class="mb-2 block text-sm font-medium text-slate-700">Growth Stage <span class="text-rose-600">*</span></label>
                        <select id="update-batch-stage" name="growth_stage" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                            <option value="">Select stage</option>
                        </select>
                    </div>
                    <div>
                        <label for="update-batch-weight" class="mb-2 block text-sm font-medium text-slate-700">Avg. Weight (kg) <span class="text-rose-600">*</span></label>
                        <input id="update-batch-weight" name="avg_weight" type="number" min="1" step="0.1" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                    </div>
                </div>

                <div>
                    <label for="update-batch-pen" class="mb-2 block text-sm font-medium text-slate-700">Assigned Pen <span class="text-rose-600">*</span></label>
                    <select id="update-batch-pen" name="assigned_pen" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200" required>
                        <option value="">Select pen</option>
                    </select>
                </div>

                <div>
                    <label for="update-batch-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="update-batch-notes" name="health_notes" rows="3" placeholder="Optional notes for this batch..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <button type="button" data-update-batch-modal-close="1" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                    <button id="confirm-update-batch-button" type="submit" class="rounded-xl bg-amber-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                        Update Batch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
