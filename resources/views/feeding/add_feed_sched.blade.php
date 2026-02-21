@php
    $showFeedingModal = request('modal') === 'add-feeding';
@endphp

<div id="feeding-schedule-modal" class="{{ $showFeedingModal ? 'fixed inset-0 z-[70] opacity-100' : 'hidden' }} transition duration-200" aria-hidden="{{ $showFeedingModal ? 'false' : 'true' }}">
    <a href="{{ route('show.feeding') }}" class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="pointer-events-auto w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-5 shadow-xl sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 id="feeding-schedule-title" class="text-lg font-semibold text-slate-900">Add Feeding Schedule</h2>
                    <p class="mt-1 text-sm text-slate-500">Set batch, time, and quantity.</p>
                </div>
                <a href="{{ route('show.feeding') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div id="feed-form-feedback" class="mt-4 hidden rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800"></div>

            <form id="feeding-schedule-form" class="mt-4 space-y-4">
                <div>
                    <label for="feed-batch-id" class="mb-1.5 block text-sm font-medium text-slate-700">Batch ID <span class="text-rose-600">*</span></label>
                    <select id="feed-batch-id" name="feed_batch_id" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                        <option value="">Choose batch ID</option>
                        <option value="BATCH-001" data-pen="pen-a" data-pigs="30 pigs" data-qty="22">BATCH-001</option>
                        <option value="BATCH-002" data-pen="pen-b" data-pigs="24 pigs" data-qty="42">BATCH-002</option>
                        <option value="BATCH-003" data-pen="pen-c" data-pigs="28 pigs" data-qty="20">BATCH-003</option>
                        <option value="BATCH-004" data-pen="pen-d" data-pigs="18 pigs" data-qty="18">BATCH-004</option>
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Target pigs: <span id="feed-batch-target" class="font-medium">Choose a batch to show exact pig count.</span></p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="feed-date" class="mb-1.5 block text-sm font-medium text-slate-700">Date <span class="text-rose-600">*</span></label>
                        <input id="feed-date" name="feed_date" type="date" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="feed-time" class="mb-1.5 block text-sm font-medium text-slate-700">Time <span class="text-rose-600">*</span></label>
                        <input id="feed-time" name="feed_time" type="time" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="feed-quantity" class="mb-1.5 block text-sm font-medium text-slate-700">Quantity (kg) <span class="text-rose-600">*</span></label>
                        <input id="feed-quantity" name="feed_quantity" type="number" step="0.5" min="1" placeholder="e.g. 42" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="feed-pen" class="mb-1.5 block text-sm font-medium text-slate-700">Pen <span class="text-rose-600">*</span></label>
                        <select id="feed-pen" name="feed_pen" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                            <option value="">Select pen</option>
                            <option value="pen-a">Pen A</option>
                            <option value="pen-b">Pen B</option>
                            <option value="pen-c">Pen C</option>
                            <option value="pen-d">Pen D</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="feed-notes" class="mb-1.5 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="feed-notes" name="feed_notes" rows="2" placeholder="Optional notes..." class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-1">
                    <a href="{{ route('show.feeding') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
