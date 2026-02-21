@php
    $showPenModal = request('modal') === 'add-pen';
@endphp

<div id="pig-pen-modal" class="{{ $showPenModal ? 'fixed inset-0 z-[90] opacity-100' : 'hidden' }} transition duration-300" aria-hidden="{{ $showPenModal ? 'false' : 'true' }}">
    <a href="{{ route('show.pig') }}" class="absolute inset-0 z-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="relative z-10 absolute inset-0 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-lg rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Pen Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Add New Pen</h2>
                    <p class="mt-2 text-sm text-slate-600">Register a pen and set basic capacity details.</p>
                </div>
                <a href="{{ route('show.pig') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div id="pen-form-feedback" class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800"></div>

            <form id="pig-pen-form" class="mt-6 space-y-4">
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Select</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" data-fill-pen-capacity="20" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">20 pigs</button>
                        <button type="button" data-fill-pen-capacity="30" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">30 pigs</button>
                        <button type="button" data-fill-pen-capacity="40" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">40 pigs</button>
                    </div>
                </section>

                <div>
                    <label for="pen-name" class="mb-2 block text-sm font-medium text-slate-700">Pen Name <span class="text-rose-600">*</span></label>
                    <input id="pen-name" name="pen_name" type="text" placeholder="e.g. Pen E" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="pen-capacity" class="mb-2 block text-sm font-medium text-slate-700">Capacity (Pigs)</label>
                        <input id="pen-capacity" name="capacity" type="number" min="1" placeholder="e.g. 30" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>
                    <div>
                        <label for="pen-status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <select id="pen-status" name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <button type="button" data-fill-pen-status="available" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">Available</button>
                            <button type="button" data-fill-pen-status="occupied" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">Occupied</button>
                            <button type="button" data-fill-pen-status="maintenance" class="rounded-full border border-slate-200 bg-white px-2.5 py-1 text-xs font-medium text-slate-700 transition hover:bg-slate-50">Maintenance</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="pen-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="pen-notes" name="notes" rows="3" placeholder="Optional notes for this pen..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <a href="{{ route('show.pig') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save Pen</button>
                </div>
            </form>
        </div>
    </div>
</div>
