@php
    $showBatchModal = request('modal') === 'add-batch';
@endphp

<div id="add-batch-modal" class="{{ $showBatchModal ? 'fixed inset-0 z-[70] opacity-100' : 'hidden' }} transition duration-300" aria-hidden="{{ $showBatchModal ? 'false' : 'true' }}">
    <a href="{{ route('show.dashboard') }}" class="absolute inset-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-3xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Batch Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Add New Pig Batch</h2>
                    <p class="mt-2 text-sm text-slate-600">Register a batch with key details for feeding and monitoring.</p>
                </div>
                <a href="{{ route('show.dashboard') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div id="batch-form-feedback" class="mt-4 {{ $errors->has('batch') ? '' : 'hidden' }} rounded-xl border {{ $errors->has('batch') ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }} px-3 py-2 text-sm font-medium">
                {{ $errors->first('batch') }}
            </div>

            <form id="add-batch-form" method="POST" action="{{ route('web.batches.add') }}" class="mt-6 space-y-5">
                @csrf
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Presets</p>
                    <div id="batch-stage-presets" class="mt-2 flex flex-wrap gap-2">
                        <span class="text-xs text-slate-500">Loading growth stages...</span>
                    </div>
                </section>

                <div>
                    <label for="batch-name" class="mb-2 block text-sm font-medium text-slate-700">Batch Name <span class="text-rose-600">*</span></label>
                    <input id="batch-name" name="batch_name" type="text" placeholder="e.g. Batch E" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="batch-count" class="mb-2 block text-sm font-medium text-slate-700">No. of Pigs <span class="text-rose-600">*</span></label>
                        <input id="batch-count" name="pig_count" type="number" min="1" placeholder="e.g. 30" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="batch-current-age" class="mb-2 block text-sm font-medium text-slate-700">Current Age (Days) <span class="text-rose-600">*</span></label>
                        <input id="batch-current-age" name="current_age_days" type="number" min="0" placeholder="e.g. 45" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                    </div>
                    <div>
                        <label for="batch-stage" class="mb-2 block text-sm font-medium text-slate-700">Growth Stage <span class="text-rose-600">*</span></label>
                        <select id="batch-stage" name="growth_stage" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
                            <option value="">Select stage</option>
                        </select>
                    </div>
                    <div>
                        <label for="batch-weight" class="mb-2 block text-sm font-medium text-slate-700">Avg. Weight (kg) <span class="text-rose-600">*</span></label>
                        <input id="batch-weight" name="avg_weight" type="number" step="0.1" min="1" placeholder="e.g. 45.5" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200" required>
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
                    <label for="batch-notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
                    <textarea id="batch-notes" name="notes" rows="3" placeholder="Optional notes for this batch..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                </div>

                <div class="flex flex-wrap justify-end gap-2 pt-2">
                    <a href="{{ route('show.dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stageSelect = document.getElementById('batch-stage');
        const presetContainer = document.getElementById('batch-stage-presets');

        if (!stageSelect || !presetContainer || stageSelect.dataset.growthBound === '1') {
            return;
        }

        stageSelect.dataset.growthBound = '1';

        const growthApiUrl = @js(route('growth.index'));

        const bindPresetActions = function () {
            const presetButtons = presetContainer.querySelectorAll('[data-fill-batch-stage]');
            presetButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const growthName = button.getAttribute('data-fill-batch-stage') ?? '';
                    stageSelect.value = growthName;
                    stageSelect.dispatchEvent(new Event('change'));
                });
            });
        };

        const renderPresets = function (growthStages) {
            if (!Array.isArray(growthStages)) {
                return;
            }

            presetContainer.innerHTML = growthStages
                .map(function (growthStage) {
                    const growthName = String(growthStage.growth_name ?? '').trim();
                    if (growthName === '') {
                        return '';
                    }

                    return `<button type="button" data-fill-batch-stage="${growthName}" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">${growthName}</button>`;
                })
                .join('');

            if (presetContainer.innerHTML.trim() === '') {
                presetContainer.innerHTML = '<span class="text-xs text-slate-500">No growth stages found.</span>';
            }

            bindPresetActions();
        };

        const renderSelectOptions = function (growthStages) {
            if (!Array.isArray(growthStages)) {
                return;
            }

            const options = growthStages
                .map(function (growthStage) {
                    const growthName = String(growthStage.growth_name ?? '').trim();
                    const growthCode = String(growthStage.growth_code ?? '').trim();
                    if (growthName === '' || growthCode === '') {
                        return '';
                    }

                    return `<option value="${growthName}" data-growth-code="${growthCode}" data-growth-name="${growthName}">${growthName}</option>`;
                })
                .join('');

            stageSelect.innerHTML = '<option value="">Select stage</option>' + options;
        };

        bindPresetActions();

        fetch(growthApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (payload) {
                const growthStages = Array.isArray(payload?.data) ? payload.data : [];
                if (growthStages.length === 0) {
                    presetContainer.innerHTML = '<span class="text-xs text-slate-500">No growth stages found.</span>';
                    return;
                }

                renderSelectOptions(growthStages);
                renderPresets(growthStages);
            })
            .catch(function () {
                presetContainer.innerHTML = '<span class="text-xs text-rose-600">Unable to load growth stages.</span>';
            });
    });
</script>
