const homeBatchForm = document.getElementById('add-batch-form');

if (homeBatchForm instanceof HTMLFormElement) {
    const growthStageInput = homeBatchForm.querySelector('#batch-stage');
    const presetButtons = homeBatchForm.querySelectorAll('[data-fill-batch-stage]');

    presetButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const stage = button.getAttribute('data-fill-batch-stage');
            if (growthStageInput instanceof HTMLSelectElement && stage) {
                growthStageInput.value = stage;
                growthStageInput.focus();
            }
        });
    });

    homeBatchForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!homeBatchForm.reportValidity()) {
            window.showWarningAlert?.({
                title: 'Cannot Save Batch',
                message: 'Please complete the required fields before saving.',
            });
            return;
        }

        const formData = new FormData(homeBatchForm);
        const csrfToken = formData.get('_token');
        const payload = {
            batch_name: formData.get('batch_name'),
            no_of_pigs: formData.get('pig_count'),
            current_age_days: formData.get('current_age_days'),
            avg_weight_kg: formData.get('avg_weight'),
            growth_stage: formData.get('growth_stage'),
            notes: formData.get('notes'),
            pen_id: formData.get('assigned_pen'),
        };

        try {
            const response = await fetch('/api/batches/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': typeof csrfToken === 'string' ? csrfToken : '',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));

            if (response.ok) {
                window.showSuccessAlert?.({
                    title: 'Saved Successfully',
                    message: data.batch_id
                        ? `${data.message ?? 'Batch details were saved.'} ID: ${data.batch_id}`
                        : (data.message ?? 'Batch details were saved.'),
                });

                homeBatchForm.reset();

                const modal = document.getElementById('add-batch-modal');
                const homePageContent = document.getElementById('home-page-content');
                modal?.classList.add('hidden');
                modal?.setAttribute('aria-hidden', 'true');
                homePageContent?.classList.remove('blur-[2px]', 'pointer-events-none', 'select-none');

                const currentUrl = new URL(window.location.href);
                if (currentUrl.searchParams.get('modal') === 'add-batch') {
                    currentUrl.searchParams.delete('modal');
                    window.history.replaceState({}, '', currentUrl.toString());
                }

                return;
            }

            const validationErrors = data.errors ? Object.values(data.errors).flat() : [];
            const firstError = validationErrors.length > 0 ? String(validationErrors[0]) : null;

            window.showWarningAlert?.({
                title: 'Saving Failed',
                message: firstError ?? data.message ?? 'Data was not saved. Please check your input and try again.',
            });
        } catch (error) {
            window.showWarningAlert?.({
                title: 'Connection Error',
                message: 'Could not save batch data right now. Please try again.',
            });
        }
    });
}
