const penForm = document.getElementById('pig-pen-form');

function buildPenCode() {
    return `PEN-${Date.now()}`;
}

function saveFrontendPen(payload) {
    const storageKey = 'smart_hog_frontend_pens';
    const existing = JSON.parse(window.localStorage.getItem(storageKey) ?? '[]');
    existing.push({
        ...payload,
        pen_code: buildPenCode(),
        created_at: new Date().toISOString(),
    });
    window.localStorage.setItem(storageKey, JSON.stringify(existing));
    return existing.at(-1);
}

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
        const payload = Object.fromEntries(formData.entries());

        try {
            const savedPen = saveFrontendPen(payload);

            window.showSuccessAlert?.({
                title: 'Saved Locally',
                message: `Pen details were saved on this device. ID: ${savedPen.pen_code}`,
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
        } catch (error) {
            window.showWarningAlert?.({
                title: 'Saving Failed',
                message: 'Could not save pen data in local storage. Please try again.',
            });
        }
    });
}
