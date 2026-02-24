@php
    $showPigModal = request('modal') === 'add-pig';
@endphp

<div id="pig-management-modal" class="{{ $showPigModal ? 'fixed inset-0 z-[70] opacity-100' : 'hidden' }} transition duration-300" aria-hidden="{{ $showPigModal ? 'false' : 'true' }}">
    <a href="{{ route('show.pig') }}" class="absolute inset-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-3xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Batch Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Add New Pig Batch</h2>
                    <p class="mt-2 text-sm text-slate-600">Register a batch with key details for feeding and monitoring.</p>
                </div>
                <a href="{{ route('show.pig') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div id="pig-form-feedback" class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800"></div>

            <form id="pig-management-form" method="POST" action="{{ route('web.batches.add') }}" class="mt-6 space-y-5">
                @csrf
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Presets</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach (($growthStages ?? collect()) as $growthStage)
                            <button type="button" data-fill-stage="{{ $growthStage->growth_name }}" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">{{ $growthStage->growth_name }}</button>
                        @endforeach
                    </div>
                </section>

                <div>
                    <label for="pig-batch-name" class="mb-2 block text-sm font-medium text-slate-700">Batch Name <span class="text-rose-600">*</span></label>
                    <input id="pig-batch-name" name="batch_name" type="text" placeholder="e.g. Batch E" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="pig-count" class="mb-2 block text-sm font-medium text-slate-700">No. of Pigs <span class="text-rose-600">*</span></label>
                        <input id="pig-count" name="pig_count" type="number" min="1" placeholder="e.g. 30" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="pig-current-age" class="mb-2 block text-sm font-medium text-slate-700">Current Age (Days) <span class="text-rose-600">*</span></label>
                        <input id="pig-current-age" name="current_age_days" type="number" min="0" placeholder="e.g. 45" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="pig-stage" class="mb-2 block text-sm font-medium text-slate-700">Growth Stage <span class="text-rose-600">*</span></label>
                        <select id="pig-stage" name="growth_stage" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                            <option value="">Select stage</option>
                            @foreach (($growthStages ?? collect()) as $growthStage)
                                <option value="{{ $growthStage->growth_name }}">{{ $growthStage->growth_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pig-weight" class="mb-2 block text-sm font-medium text-slate-700">Avg. Weight (kg) <span class="text-rose-600">*</span></label>
                        <input id="pig-weight" name="avg_weight" type="number" step="0.1" min="1" placeholder="e.g. 45.5" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                </div>

                <div>
                    <label for="pig-pen" class="mb-2 block text-sm font-medium text-slate-700">Assigned Pen <span class="text-rose-600">*</span></label>
                    <select id="pig-pen" name="assigned_pen" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                        <option value="">Select pen</option>
                        @foreach (($pens ?? collect()) as $pen)
                            <option value="{{ $pen->pen_code }}">{{ $pen->pen_name }} ({{ $pen->pen_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pig-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="pig-notes" name="health_notes" rows="3" placeholder="Optional notes for this batch..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <a href="{{ route('show.pig') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
