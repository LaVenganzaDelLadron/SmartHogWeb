const pigModal = document.getElementById('pig-management-modal');
const pigPageContent = document.getElementById('pig-page-content');
const openPigModalButtons = document.querySelectorAll('[data-open-pig-modal]');
const closePigModalButtons = document.querySelectorAll('[data-close-pig-modal]');
const pigManagementForm = document.getElementById('pig-management-form');
const pigCountInput = document.getElementById('pig-count');
const pigStageInput = document.getElementById('pig-stage');
const pigWeightInput = document.getElementById('pig-weight');
const pigFormFeedback = document.getElementById('pig-form-feedback');
const quickStageButtons = document.querySelectorAll('[data-fill-stage]');

function openPigModal() {
    if (!pigModal || !pigPageContent) {
        return;
    }

    pigModal.classList.remove('pointer-events-none', 'opacity-0');
    pigModal.setAttribute('aria-hidden', 'false');
    pigPageContent.classList.add('blur-sm');
    document.body.classList.add('overflow-hidden');

    if (pigFormFeedback) {
        pigFormFeedback.classList.add('hidden');
    }

    window.setTimeout(() => {
        pigCountInput?.focus();
    }, 100);
}

function closePigModal() {
    if (!pigModal || !pigPageContent) {
        return;
    }

    pigModal.classList.add('pointer-events-none', 'opacity-0');
    pigModal.setAttribute('aria-hidden', 'true');
    pigPageContent.classList.remove('blur-sm');
    document.body.classList.remove('overflow-hidden');
}

openPigModalButtons.forEach((button) => {
    button.addEventListener('click', openPigModal);
});

closePigModalButtons.forEach((button) => {
    button.addEventListener('click', closePigModal);
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closePigModal();
    }
});

quickStageButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const value = button.getAttribute('data-fill-stage');
        if (!pigStageInput || !value) {
            return;
        }

        pigStageInput.value = value;

        if (pigWeightInput && !pigWeightInput.value) {
            const suggestedWeight = value === 'weaner' ? '31.0' : (value === 'grower' ? '48.0' : '64.0');
            pigWeightInput.value = suggestedWeight;
        }
    });
});

pigManagementForm?.addEventListener('submit', (event) => {
    event.preventDefault();

    if (!pigManagementForm.reportValidity()) {
        return;
    }

    if (pigFormFeedback) {
        pigFormFeedback.textContent = 'Pig batch saved successfully.';
        pigFormFeedback.classList.remove('hidden');
    }

    window.setTimeout(() => {
        pigManagementForm.reset();
        closePigModal();
    }, 700);
});
