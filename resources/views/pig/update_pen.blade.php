<div id="pig-edit-pen-modal" class="fixed inset-0 z-[90] hidden transition duration-300" aria-hidden="true">
    <button type="button" data-update-modal-close="1" class="absolute inset-0 z-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></button>

    <div class="absolute inset-0 z-10 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-2xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Pen Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Update Pen</h2>
                    <p class="mt-2 text-sm text-slate-600">Edit pen details and review latest pen information before saving.</p>
                </div>
                <button type="button" data-update-modal-close="1" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </button>
            </div>

            <div class="mt-5 grid gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">Pen ID</p>
                    <p id="update-pen-code-preview" class="mt-1 text-sm font-semibold text-emerald-900">N/A</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">Current Status</p>
                    <p id="update-pen-status-preview" class="mt-1 text-sm font-semibold capitalize text-emerald-900">available</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">Capacity</p>
                    <p id="update-pen-capacity-preview" class="mt-1 text-sm font-semibold text-emerald-900">0 pigs</p>
                </div>
            </div>

            <form id="pig-update-pen-form" class="mt-6 space-y-4">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="update-pen-name" class="mb-2 block text-sm font-medium text-slate-700">Pen Name</label>
                        <input id="update-pen-name" name="pen_name" type="text" value="" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="update-pen-capacity" class="mb-2 block text-sm font-medium text-slate-700">Capacity</label>
                        <input id="update-pen-capacity" name="capacity" type="number" min="1" value="" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="update-pen-status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <select id="update-pen-status" name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="update-pen-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="update-pen-notes" name="notes" rows="4" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <button type="button" data-update-modal-close="1" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                    <button id="confirm-update-pen-button" type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">
                        Update Pen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
