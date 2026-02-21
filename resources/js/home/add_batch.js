const batchModal = document.getElementById('add-batch-modal');
const homePageContent = document.getElementById('home-page-content');
const openBatchModalButtons = document.querySelectorAll('[data-open-batch-modal]');
const closeBatchModalButtons = document.querySelectorAll('[data-close-batch-modal]');
const addBatchForm = document.getElementById('add-batch-form');
const batchCountInput = document.getElementById('batch-count');
const batchStageInput = document.getElementById('batch-stage');
const batchWeightInput = document.getElementById('batch-weight');
const batchFormFeedback = document.getElementById('batch-form-feedback');
const quickBatchStageButtons = document.querySelectorAll('[data-fill-batch-stage]');

function openBatchModal() {
    if (!batchModal || !homePageContent) {
        return;
    }

    batchModal.classList.remove('pointer-events-none', 'opacity-0');
    batchModal.setAttribute('aria-hidden', 'false');
    homePageContent.classList.add('blur-sm');
    document.body.classList.add('overflow-hidden');

    if (batchFormFeedback) {
        batchFormFeedback.classList.add('hidden');
    }

    window.setTimeout(() => {
        batchCountInput?.focus();
    }, 100);
}

function closeBatchModal() {
    if (!batchModal || !homePageContent) {
        return;
    }

    batchModal.classList.add('pointer-events-none', 'opacity-0');
    batchModal.setAttribute('aria-hidden', 'true');
    homePageContent.classList.remove('blur-sm');
    document.body.classList.remove('overflow-hidden');
}

openBatchModalButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
        if (event.currentTarget instanceof HTMLAnchorElement) {
            event.preventDefault();
        }
        openBatchModal();
    });
});

closeBatchModalButtons.forEach((button) => {
    button.addEventListener('click', closeBatchModal);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeBatchModal();
    }
});

quickBatchStageButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-batch-stage');
        if (!batchStageInput || !value) {
            return;
        }

        batchStageInput.value = value;

        if (batchWeightInput && !batchWeightInput.value) {
            const suggestedWeight = value === 'weaner' ? '30.0' : (value === 'grower' ? '46.0' : '62.0');
            batchWeightInput.value = suggestedWeight;
        }
    });
});

addBatchForm?.addEventListener('submit', (event) => {
    event.preventDefault();

    if (!addBatchForm.reportValidity()) {
        return;
    }

    if (batchFormFeedback) {
        batchFormFeedback.textContent = 'Batch saved successfully.';
        batchFormFeedback.classList.remove('hidden');
    }

    window.setTimeout(() => {
        addBatchForm.reset();
        closeBatchModal();
    }, 700);
});
