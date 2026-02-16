import { auth } from '../firebase';

const profileSelector = '[data-profile-name]';

function setProfileName(name) {
    if (!name) return;

    document.querySelectorAll(profileSelector).forEach((el) => {
        el.textContent = name;
    });
}

function getFallbackName() {
    const el = document.querySelector(profileSelector);
    return el?.dataset?.profileName || 'Profile';
}

function initProfileName() {
    // Keep server-rendered full name as initial fallback.
    setProfileName(getFallbackName());

    auth.onAuthStateChanged((user) => {
        if (!user) return;

        const nameFromFirebase = user.displayName || user.email?.split('@')[0] || '';
        if (nameFromFirebase) {
            setProfileName(nameFromFirebase);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProfileName);
} else {
    initProfileName();
}
