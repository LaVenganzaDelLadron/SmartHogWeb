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

            <form id="pig-pen-form" action="{{ route('api.pens.add') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Select</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" data-fill-pen-capacity="10" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50" onclick="quickSelectCapacity(1)">10 pigs</button>
                        <button type="button" data-fill-pen-capacity="20" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50" onclick="quickSelectCapacity(2)">20 pigs</button>
                        <button type="button" data-fill-pen-capacity="30" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50" onclick="quickSelectCapacity(3)">30 pigs</button>
                        <button type="button" data-fill-pen-capacity="40" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50" onclick="quickSelectCapacity(4)">40 pigs</button>
                    </div>
                </section>

                <div>
                    <label for="pen-name" class="mb-2 block text-sm font-medium text-slate-700">Pen Name <span class="text-rose-600">*</span></label>
                    <input id="pen-name" name="pen_name" type="text" placeholder="e.g. Pen E" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="pen-capacity" class="mb-2 block text-sm font-medium text-slate-700">Capacity (Pigs)</label>
                        <input id="pen-capacity" name="capacity" type="number" min="1" step="1" placeholder="e.g. 30" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
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
<script>
    function quickSelectCapacity(capacity)
    {
        const values = {
            1: 10,
            2: 20,
            3: 30,
            4: 40,
        };

        const capacityInput = document.getElementById('pen-capacity');
        if (capacityInput && values[capacity]) {
            capacityInput.value = values[capacity];
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const penForm = document.getElementById('pig-pen-form');

        if (! penForm || penForm.dataset.bound === '1') {
            return;
        }

        penForm.dataset.bound = '1';

        const showFeedback = function (message, isError = false) {
            if (isError && typeof window.showWarningAlert === 'function') {
                window.showWarningAlert({
                    title: 'Add Pen Failed',
                    message: message,
                    durationMs: 3200,
                });
                return;
            }

            if (! isError && typeof window.showSuccessAlert === 'function') {
                window.showSuccessAlert({
                    title: 'Pen Added',
                    message: message,
                    durationMs: 2400,
                });
            }
        };

        const setSubmitting = function (button, submitting) {
            if (! (button instanceof HTMLButtonElement)) {
                return;
            }

            button.disabled = submitting;
            button.classList.toggle('opacity-70', submitting);
            button.classList.toggle('cursor-not-allowed', submitting);
        };

        penForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const payload = {
                pen_name: String(document.getElementById('pen-name')?.value || '').trim(),
                capacity: Number(document.getElementById('pen-capacity')?.value || 0),
                status: 'available',
                notes: String(document.getElementById('pen-notes')?.value || '').trim(),
            };

            const submitButton = penForm.querySelector('button[type="submit"]');
            setSubmitting(submitButton, true);

            try {
                const response = await fetch('{{ route('api.pens.add') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload),
                });

                const result = await response.json();
                if (! response.ok || ! result?.ok) {
                    const errorMessage = typeof result?.message === 'string' && result.message.trim() !== ''
                        ? result.message
                        : 'Failed to add pen. Please try again.';

                    showFeedback(errorMessage, true);
                    return;
                }

                showFeedback(result.message || 'Pen added successfully.');
                penForm.reset();
            } catch (error) {
                showFeedback('Unable to connect right now. Please try again.', true);
            } finally {
                setSubmitting(submitButton, false);
            }
        });
    });

</script>
