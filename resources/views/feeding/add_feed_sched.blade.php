<div id="feeding-schedule-modal" class="pointer-events-none fixed inset-0 z-[70] opacity-0 transition duration-300" aria-hidden="true">
    <div id="feeding-schedule-backdrop" data-close-feeding-modal class="absolute inset-0 bg-slate-900/35 backdrop-blur-sm"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-2xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Create Schedule</p>
                    <h2 id="feeding-schedule-title" class="mt-2 text-2xl font-semibold text-slate-900">Add Feeding Schedule</h2>
                    <p class="mt-2 text-sm text-slate-600">Set a complete feeding plan in less than a minute.</p>
                </div>
                <button type="button" data-close-feeding-modal class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <div id="feed-form-feedback" class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800"></div>

            <form id="feeding-schedule-form" class="mt-6 space-y-5">
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Presets</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" data-fill-time="07:00" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Morning 7:00 AM</button>
                        <button type="button" data-fill-time="11:30" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Midday 11:30 AM</button>
                        <button type="button" data-fill-time="15:30" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Afternoon 3:30 PM</button>
                        <button type="button" data-fill-time="18:00" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">Evening 6:00 PM</button>
                    </div>
                </section>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="feed-time" class="mb-2 block text-sm font-medium text-slate-700">Feeding Time <span class="text-rose-600">*</span></label>
                        <input id="feed-time" name="feed_time" type="time" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                        <p class="mt-1 text-xs text-slate-500">When the dispenser should release feed.</p>
                    </div>
                    <div>
                        <label for="feed-date" class="mb-2 block text-sm font-medium text-slate-700">Date <span class="text-rose-600">*</span></label>
                        <input id="feed-date" name="feed_date" type="date" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                        <p class="mt-1 text-xs text-slate-500">Defaults to today.</p>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="feed-quantity" class="mb-2 block text-sm font-medium text-slate-700">Feed Quantity (kg) <span class="text-rose-600">*</span></label>
                        <div class="flex items-center gap-2">
                            <button type="button" data-quantity-step="-1" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" aria-label="Decrease quantity">-</button>
                            <input id="feed-quantity" name="feed_quantity" type="number" step="0.5" min="1" placeholder="e.g. 42" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                            <button type="button" data-quantity-step="1" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" aria-label="Increase quantity">+</button>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" data-fill-quantity="20" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">20 kg</button>
                            <button type="button" data-fill-quantity="30" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">30 kg</button>
                            <button type="button" data-fill-quantity="40" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">40 kg</button>
                        </div>
                    </div>
                    <div>
                        <label for="feed-pen" class="mb-2 block text-sm font-medium text-slate-700">Pig Group/Pen <span class="text-rose-600">*</span></label>
                        <select id="feed-pen" name="feed_pen" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                            <option value="">Select pen</option>
                            <option value="pen-a">Pen A · Grower</option>
                            <option value="pen-b">Pen B · Finisher</option>
                            <option value="pen-c">Pen C · Weaner</option>
                            <option value="pen-d">Pen D · Isolated</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Choose where this schedule will apply.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="repeat_daily" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-300">
                        Repeat this schedule daily
                    </label>
                    <p class="mt-1 text-xs text-slate-500">Use this for fixed routines so you do not need to re-enter each day.</p>
                </div>

                <div>
                    <label for="feed-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="feed-notes" name="feed_notes" rows="3" placeholder="Optional notes for this schedule..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <button type="button" data-close-feeding-modal class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
