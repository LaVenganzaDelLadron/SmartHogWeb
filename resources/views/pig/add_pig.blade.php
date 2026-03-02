@php
    $showPigModal = request('modal') === 'add-pig';
@endphp

<div id="pig-management-modal" class="{{ $showPigModal ? 'fixed inset-0 z-[70] opacity-100' : 'hidden' }} transition duration-300" aria-hidden="{{ $showPigModal ? 'false' : 'true' }}">
    <a href="{{ route('show.pig') }}" data-close-pig-modal="1" class="absolute inset-0 bg-slate-900/35 backdrop-blur-sm" aria-label="Close modal"></a>

    <div class="absolute inset-0 flex items-center justify-center p-4 sm:p-6">
        <div class="pointer-events-auto w-full max-w-3xl rounded-3xl border border-emerald-100 bg-white p-6 shadow-2xl sm:p-7">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Batch Setup</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Add New Pig Batch</h2>
                    <p class="mt-2 text-sm text-slate-600">Register a batch with key details for feeding and monitoring.</p>
                </div>
                <a href="{{ route('show.pig') }}" data-close-pig-modal="1" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700" aria-label="Close modal">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M5 5l10 10M15 5 5 15" />
                    </svg>
                </a>
            </div>

            <div id="pig-form-feedback" class="mt-4 {{ $errors->has('batch') ? '' : 'hidden' }} rounded-xl border {{ $errors->has('batch') ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-800' }} px-3 py-2 text-sm font-medium">
                {{ $errors->first('batch') }}
            </div>

            <form id="pig-management-form" method="POST" action="{{ route('api.batch.add') }}" class="mt-6 space-y-5">
                @csrf
                <input type="hidden" id="pig-pen-code" name="pen_code" value="">
                <input type="hidden" id="pig-pen-capacity" name="pen_capacity" value="">
                <input type="hidden" id="pig-growth-stage-code" name="growth_stage_code" value="">
                <section class="rounded-2xl border border-slate-200 bg-slate-50/60 p-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-500">Quick Presets</p>
                    <div id="pig-stage-presets" class="mt-2 flex flex-wrap gap-2">
                        <span class="text-xs text-slate-500">Loading growth stages...</span>
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
                    <a href="{{ route('show.pig') }}" data-close-pig-modal="1" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2">Save Batch</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stageSelect = document.getElementById('pig-stage');
        const presetContainer = document.getElementById('pig-stage-presets');
        const penSelect = document.getElementById('pig-pen');
        const penCodeInput = document.getElementById('pig-pen-code');
        const penCapacityInput = document.getElementById('pig-pen-capacity');
        const growthStageCodeInput = document.getElementById('pig-growth-stage-code');
        const batchForm = document.getElementById('pig-management-form');
        let isClosingModal = false;

        if (! stageSelect || ! presetContainer || ! penSelect || ! penCodeInput || ! penCapacityInput || ! growthStageCodeInput || ! batchForm || stageSelect.dataset.growthBound === '1') {
            return;
        }

        stageSelect.dataset.growthBound = '1';
        document.querySelectorAll('[data-close-pig-modal="1"]').forEach(function (element) {
            element.addEventListener('click', function () {
                isClosingModal = true;
            });
        });

        const growthApiUrl = '{{ route('api.growth.all') }}';
        const pensApiUrl = '{{ route('api.pens.all') }}';
        const growthCacheKey = 'smarthog:growth:stages:v1';
        const pensCacheKey = 'smarthog:pens:all:v1';

        const bindPresetActions = function () {
            const presetButtons = presetContainer.querySelectorAll('[data-fill-pig-stage]');
            presetButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const growthName = button.getAttribute('data-fill-pig-stage') ?? '';
                    stageSelect.value = growthName;
                    stageSelect.dispatchEvent(new Event('change'));
                });
            });
        };

        const syncGrowthStageCode = function () {
            const selectedOption = stageSelect.options[stageSelect.selectedIndex];
            const growthCode = selectedOption ? String(selectedOption.getAttribute('data-growth-code') ?? '').trim() : '';
            growthStageCodeInput.value = growthCode;
        };

        const syncPenCode = function () {
            penCodeInput.value = String(penSelect.value ?? '').trim();
            const selectedOption = penSelect.options[penSelect.selectedIndex];
            const selectedCapacity = selectedOption ? String(selectedOption.getAttribute('data-pen-capacity') ?? '').trim() : '';
            penCapacityInput.value = selectedCapacity;
        };

        const renderPresets = function (growthStages) {
            if (! Array.isArray(growthStages)) {
                return;
            }

            presetContainer.innerHTML = growthStages
                .map(function (growthStage) {
                    const growthName = String(growthStage.growth_name ?? '').trim();
                    if (growthName === '') {
                        return '';
                    }

                    return `<button type="button" data-fill-pig-stage="${growthName}" class="rounded-full border border-emerald-200 bg-white px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">${growthName}</button>`;
                })
                .join('');

            if (presetContainer.innerHTML.trim() === '') {
                presetContainer.innerHTML = '<span class="text-xs text-slate-500">No growth stages found.</span>';
            }

            bindPresetActions();
        };

        const renderSelectOptions = function (growthStages) {
            if (! Array.isArray(growthStages)) {
                return;
            }

            const options = growthStages
                .map(function (growthStage) {
                    const growthName = String(growthStage.growth_name ?? '').trim();
                    const growthCode = String(growthStage.growth_code ?? '').trim();
                    if (growthName === '') {
                        return '';
                    }

                    return `<option value="${growthName}" data-growth-code="${growthCode}">${growthName}</option>`;
                })
                .join('');

            stageSelect.innerHTML = '<option value="">Select stage</option>' + options;
            syncGrowthStageCode();
        };

        const readCachedGrowthStages = function () {
            try {
                const cached = sessionStorage.getItem(growthCacheKey);
                if (! cached) {
                    return null;
                }

                const parsed = JSON.parse(cached);
                return Array.isArray(parsed) ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeCachedGrowthStages = function (growthStages) {
            try {
                sessionStorage.setItem(growthCacheKey, JSON.stringify(growthStages));
            } catch (error) {
                // Ignore cache write failures.
            }
        };

        const renderPenOptions = function (pens) {
            if (! Array.isArray(pens)) {
                return;
            }

            const options = pens
                .map(function (pen) {
                    const penCode = String(pen.pen_code ?? '').trim();
                    const penName = String(pen.pen_name ?? '').trim();
                    const penCapacity = String(pen.capacity ?? '').trim();
                    if (penCode === '' || penName === '') {
                        return '';
                    }

                    return `<option value="${penCode}" data-pen-capacity="${penCapacity}">${penName} (${penCode})</option>`;
                })
                .join('');

            penSelect.innerHTML = '<option value="">Select pen</option>' + options;
        };

        const readCachedPens = function () {
            try {
                const cached = sessionStorage.getItem(pensCacheKey);
                if (! cached) {
                    return null;
                }

                const parsed = JSON.parse(cached);
                return Array.isArray(parsed) ? parsed : null;
            } catch (error) {
                return null;
            }
        };

        const writeCachedPens = function (pens) {
            try {
                sessionStorage.setItem(pensCacheKey, JSON.stringify(pens));
            } catch (error) {
                // Ignore cache write failures.
            }
        };

        const cachedGrowthStages = readCachedGrowthStages();
        if (Array.isArray(cachedGrowthStages) && cachedGrowthStages.length > 0) {
            renderSelectOptions(cachedGrowthStages);
            renderPresets(cachedGrowthStages);
        }

        const cachedPens = readCachedPens();
        if (Array.isArray(cachedPens) && cachedPens.length > 0) {
            renderPenOptions(cachedPens);
        }

        fetch(growthApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
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
                writeCachedGrowthStages(growthStages);
            })
            .catch(function () {
                if (isClosingModal) {
                    return;
                }

                presetContainer.innerHTML = '<span class="text-xs text-rose-600">Unable to load growth stages.</span>';
            });

        fetch(pensApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json();
            })
            .then(function (payload) {
                const source = payload?.data;
                const pens = Array.isArray(source?.data) ? source.data : (Array.isArray(source) ? source : []);
                if (pens.length === 0) {
                    penSelect.innerHTML = '<option value="">No pens found</option>';
                    return;
                }

                renderPenOptions(pens);
                writeCachedPens(pens);
                syncPenCode();
            })
            .catch(function () {
                if (isClosingModal) {
                    return;
                }

                penSelect.innerHTML = '<option value="">Unable to load pens</option>';
                syncPenCode();
            });

        stageSelect.addEventListener('change', syncGrowthStageCode);
        penSelect.addEventListener('change', syncPenCode);
        syncGrowthStageCode();
        syncPenCode();

        batchForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            const formData = new FormData(batchForm);
            const payload = Object.fromEntries(formData.entries());

            const submitButton = batchForm.querySelector('button[type="submit"]');
            if (submitButton instanceof HTMLButtonElement) {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-70', 'cursor-not-allowed');
            }

            try {
                const response = await fetch(batchForm.getAttribute('action') || '{{ route('api.batch.add') }}', {
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
                    const message = typeof result?.message === 'string' && result.message.trim() !== ''
                        ? result.message
                        : 'Failed to add batch. Please try again.';

                    if (typeof window.showWarningAlert === 'function') {
                        window.showWarningAlert({
                            title: 'Add Batch Failed',
                            message: message,
                            durationMs: 3400,
                        });
                    }
                    return;
                }

                if (typeof window.showSuccessAlert === 'function') {
                    window.showSuccessAlert({
                        title: 'Batch Added',
                        message: result.message || 'Batch added successfully.',
                        durationMs: 2400,
                    });
                }

                batchForm.reset();
                syncGrowthStageCode();
                syncPenCode();
            } catch (error) {
                if (typeof window.showWarningAlert === 'function') {
                    window.showWarningAlert({
                        title: 'Add Batch Failed',
                        message: 'Unable to submit batch right now. Please try again.',
                        durationMs: 3400,
                    });
                }
            } finally {
                if (submitButton instanceof HTMLButtonElement) {
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            }
        });
    });
</script>
