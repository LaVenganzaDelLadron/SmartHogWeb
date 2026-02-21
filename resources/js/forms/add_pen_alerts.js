const penForm = document.getElementById('pig-pen-form');

if (penForm instanceof HTMLFormElement) {
    penForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!penForm.reportValidity()) {
            window.showWarningAlert?.({
                title: 'Cannot Save Pen',
                message: 'Please complete the required fields before saving.',
            });
            return;
        }

        const formData = new FormData(penForm);
        const csrfToken = formData.get('_token');
        const payload = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('/api/pens/add', {
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
                    message: data.pen_code
                        ? `${data.message ?? 'Pen details were saved.'} ID: ${data.pen_code}`
                        : (data.message ?? 'Pen details were saved.'),
                });

                penForm.reset();

                const modal = document.getElementById('pig-pen-modal');
                const pigPageContent = document.getElementById('pig-page-content');
                modal?.classList.add('hidden');
                modal?.setAttribute('aria-hidden', 'true');
                pigPageContent?.classList.remove('blur-[2px]', 'pointer-events-none', 'select-none');

                const currentUrl = new URL(window.location.href);
                if (currentUrl.searchParams.get('modal') === 'add-pen') {
                    currentUrl.searchParams.delete('modal');
                    window.history.replaceState({}, '', currentUrl.toString());
                }

                return;
            }

            if (response.status === 409) {
                window.showWarningAlert?.({
                    title: 'Duplicate Pen Name',
                    message: data.message ?? 'Pen name already exists. Use a different name.',
                });
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
                message: 'Could not save pen data right now. Please try again.',
            });
        }
    });
}
