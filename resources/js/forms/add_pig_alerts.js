const pigForm = document.getElementById('pig-management-form');

function buildBatchId() {
    return `BATCH-${Date.now()}`;
}

function saveFrontendBatch(payload) {
    const storageKey = 'smart_hog_frontend_batches';
    const existing = JSON.parse(window.localStorage.getItem(storageKey) ?? '[]');
    existing.push({
        ...payload,
        batch_id: buildBatchId(),
        created_at: new Date().toISOString(),
    });
    window.localStorage.setItem(storageKey, JSON.stringify(existing));
    return existing.at(-1);
}

if (pigForm instanceof HTMLFormElement) {
    const growthStageInput = pigForm.querySelector('#pig-stage');
    const presetButtons = pigForm.querySelectorAll('[data-fill-stage]');

    presetButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const stage = button.getAttribute('data-fill-stage');
            if (growthStageInput instanceof HTMLSelectElement && stage) {
                growthStageInput.value = stage;
                growthStageInput.focus();
            }
        });
    });

    pigForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!pigForm.reportValidity()) {
            window.showWarningAlert?.({
                title: 'Cannot Save Batch',
                message: 'Please complete the required fields before saving.',
            });
            return;
        }

        const formData = new FormData(pigForm);
        const payload = {
            batch_name: formData.get('batch_name'),
            no_of_pigs: formData.get('pig_count'),
            current_age_days: formData.get('current_age_days'),
            avg_weight_kg: formData.get('avg_weight'),
            growth_stage: formData.get('growth_stage'),
            notes: formData.get('health_notes'),
            pen_id: formData.get('assigned_pen'),
        };

        try {
            const savedBatch = saveFrontendBatch(payload);

            window.showSuccessAlert?.({
                title: 'Saved Locally',
                message: `Batch details were saved on this device. ID: ${savedBatch.batch_id}`,
            });

            pigForm.reset();

            const modal = document.getElementById('pig-management-modal');
            const pigPageContent = document.getElementById('pig-page-content');
            modal?.classList.add('hidden');
            modal?.setAttribute('aria-hidden', 'true');
            pigPageContent?.classList.remove('blur-[2px]', 'pointer-events-none', 'select-none');

            const currentUrl = new URL(window.location.href);
            if (currentUrl.searchParams.get('modal') === 'add-pig') {
                currentUrl.searchParams.delete('modal');
                window.history.replaceState({}, '', currentUrl.toString());
            }
        } catch (error) {
            window.showWarningAlert?.({
                title: 'Saving Failed',
                message: 'Could not save batch data in local storage. Please try again.',
            });
        }
    });
}
