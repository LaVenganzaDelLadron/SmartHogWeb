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
const quickQuantityButtons = document.querySelectorAll('[data-fill-quantity]');
const quantityStepButtons = document.querySelectorAll('[data-quantity-step]');
const quickBatchButtons = document.querySelectorAll('[data-fill-batch-id]');
const quickFeedingTypeButtons = document.querySelectorAll('[data-fill-feeding-type]');

function setDefaultDate() {
    if (!feedDateInput || feedDateInput.value) {
        return;
    }

    const today = new Date();
    const date = today.toISOString().split('T')[0];
    feedDateInput.value = date;
}

function setDefaultTime() {
    if (!feedTimeInput || feedTimeInput.value) {
        return;
    }

    feedTimeInput.value = '07:00';
}

function openFeedingModal() {
    if (!feedingModal || !feedingPageContent) {
        return;
    }

    setDefaultDate();
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

feedingScheduleForm?.addEventListener('submit', (event) => {
    event.preventDefault();

    if (!feedingScheduleForm.reportValidity()) {
        return;
    }

    if (feedFormFeedback) {
        feedFormFeedback.textContent = 'Schedule saved successfully.';
        feedFormFeedback.classList.remove('hidden');
    }

    window.setTimeout(() => {
        feedingScheduleForm.reset();
        if (feedBatchTarget) {
            feedBatchTarget.textContent = 'Choose a batch to show exact pig count.';
        }
        setDefaultDate();
        setDefaultTime();
        closeFeedingModal();
    }, 700);
});
