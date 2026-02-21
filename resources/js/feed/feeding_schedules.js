const feedingModal = document.getElementById('feeding-schedule-modal');
const feedingPageContent = document.getElementById('feeding-page-content');
const openFeedingModalButtons = document.querySelectorAll('[data-open-feeding-modal]');
const closeFeedingModalButtons = document.querySelectorAll('[data-close-feeding-modal]');
const feedingScheduleForm = document.getElementById('feeding-schedule-form');
const feedDateInput = document.getElementById('feed-date');
const feedTimeInput = document.getElementById('feed-time');
const feedQuantityInput = document.getElementById('feed-quantity');
const feedTypeInput = document.getElementById('feed-type');
const feedBatchIdInput = document.getElementById('feed-batch-id');
const feedBatchTarget = document.getElementById('feed-batch-target');
const feedFormFeedback = document.getElementById('feed-form-feedback');
const quickTimeButtons = document.querySelectorAll('[data-fill-time]');
const quickDateButtons = document.querySelectorAll('[data-fill-date]');
const quickQuantityButtons = document.querySelectorAll('[data-fill-quantity]');
const quantityStepButtons = document.querySelectorAll('[data-quantity-step]');
const quickBatchButtons = document.querySelectorAll('[data-fill-batch-id]');
const quickFeedingTypeButtons = document.querySelectorAll('[data-fill-feeding-type]');

function formatLocalDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

function setDefaultDate() {
    if (!feedDateInput) {
        return;
    }

    feedDateInput.value = formatLocalDate(new Date());
}

function setDateMinimum() {
    if (!feedDateInput) {
        return;
    }

    feedDateInput.min = formatLocalDate(new Date());
}

function enforceDateNotInPast() {
    if (!feedDateInput || !feedDateInput.value) {
        return;
    }

    if (feedDateInput.value < feedDateInput.min) {
        feedDateInput.setCustomValidity('Past dates are not allowed.');
    } else {
        feedDateInput.setCustomValidity('');
    }
}

function setDefaultTime() {
    if (!feedTimeInput) {
        return;
    }

    const now = new Date();
    feedTimeInput.value = now.toTimeString().slice(0, 5);
}

function openFeedingModal() {
    if (!feedingModal || !feedingPageContent) {
        return;
    }

    setDefaultDate();
    setDateMinimum();
    setDefaultTime();
    feedingModal.classList.remove('pointer-events-none', 'opacity-0');
    feedingModal.setAttribute('aria-hidden', 'false');
    feedingPageContent.classList.add('blur-sm');
    document.body.classList.add('overflow-hidden');

    if (feedFormFeedback) {
        feedFormFeedback.classList.add('hidden');
    }

    window.setTimeout(() => {
        feedTimeInput?.focus();
    }, 100);
}

function closeFeedingModal() {
    if (!feedingModal || !feedingPageContent) {
        return;
    }

    feedingModal.classList.add('pointer-events-none', 'opacity-0');
    feedingModal.setAttribute('aria-hidden', 'true');
    feedingPageContent.classList.remove('blur-sm');
    document.body.classList.remove('overflow-hidden');
}

openFeedingModalButtons.forEach((button) => {
    button.addEventListener('click', openFeedingModal);
});

closeFeedingModalButtons.forEach((button) => {
    button.addEventListener('click', closeFeedingModal);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeFeedingModal();
    }
});

function applyBatchSelection(batchId) {
    if (!feedBatchIdInput || !batchId) {
        return;
    }

    feedBatchIdInput.value = batchId;
    const selectedOption = feedBatchIdInput.selectedOptions[0];
    if (!selectedOption) {
        return;
    }

    const targetPigs = selectedOption.getAttribute('data-pigs');
    const suggestedQuantity = selectedOption.getAttribute('data-qty');

    if (feedBatchTarget) {
        feedBatchTarget.textContent = targetPigs || 'No pig count available for this batch.';
    }

    if (feedQuantityInput && suggestedQuantity && !feedQuantityInput.value) {
        feedQuantityInput.value = suggestedQuantity;
    }
}

quickTimeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-time');
        if (feedTimeInput && value) {
            feedTimeInput.value = value;
            feedTimeInput.focus();
        }
    });
});

quickDateButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (!feedDateInput) {
            return;
        }

        const value = button.getAttribute('data-fill-date');
        const today = new Date();
        const date = new Date(today);

        if (value === 'tomorrow') {
            date.setDate(today.getDate() + 1);
        }

        const normalizedDate = formatLocalDate(date);
        feedDateInput.value = normalizedDate;
        enforceDateNotInPast();
        feedDateInput.focus();
    });
});

quickQuantityButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-quantity');
        if (feedQuantityInput && value) {
            feedQuantityInput.value = value;
            feedQuantityInput.focus();
        }
    });
});

quickBatchButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-batch-id');
        if (!value) {
            return;
        }
        applyBatchSelection(value);
        feedBatchIdInput?.focus();
    });
});

quickFeedingTypeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-feeding-type');
        if (feedTypeInput && value) {
            feedTypeInput.value = value;
            feedTypeInput.focus();
        }
    });
});

feedBatchIdInput?.addEventListener('change', () => {
    applyBatchSelection(feedBatchIdInput.value);
});

feedDateInput?.addEventListener('input', enforceDateNotInPast);

quantityStepButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (!feedQuantityInput) {
            return;
        }

        const step = Number(button.getAttribute('data-quantity-step') || 0);
        const currentValue = Number(feedQuantityInput.value || 0);
        const nextValue = Math.max(1, currentValue + step);
        feedQuantityInput.value = String(nextValue);
        feedQuantityInput.focus();
    });
});

feedingScheduleForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    enforceDateNotInPast();

    if (!feedingScheduleForm.reportValidity()) {
        window.showWarningAlert?.({
            title: 'Cannot Save Schedule',
            message: 'Please complete the required fields before saving.',
        });
        return;
    }

    const formData = new FormData(feedingScheduleForm);
    const csrfToken = formData.get('_token');
    const payload = {
        batch_id: formData.get('feed_batch_id'),
        feeding_quantity_kg: formData.get('feed_quantity'),
        feeding_time: formData.get('feed_time'),
        feeding_date: formData.get('feed_date'),
        feeding_type: formData.get('feed_type'),
    };

    try {
        const response = await fetch('/api/feeding/schedules/add', {
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
            closeFeedingModal();

            window.showSuccessAlert?.({
                title: 'Saved Successfully',
                message: data.feeding_id
                    ? `${data.message ?? 'Feeding schedule was saved.'} ID: ${data.feeding_id}`
                    : (data.message ?? 'Feeding schedule was saved.'),
            });

            feedingScheduleForm.reset();
            if (feedBatchTarget) {
                feedBatchTarget.textContent = 'Choose a batch to show exact pig count.';
            }
            setDefaultDate();
            setDefaultTime();
            return;
        }

        const validationErrors = data.errors ? Object.values(data.errors).flat() : [];
        const firstError = validationErrors.length > 0 ? String(validationErrors[0]) : null;

        window.showWarningAlert?.({
            title: 'Saving Failed',
            message: firstError ?? data.message ?? 'Schedule was not saved. Please check your input and try again.',
        });
    } catch (error) {
        window.showWarningAlert?.({
            title: 'Connection Error',
            message: 'Could not save feeding schedule right now. Please try again.',
        });
    }
});

setDateMinimum();
