import { doc, getDoc } from 'firebase/firestore';
import { onAuthStateChanged } from 'firebase/auth';
import { auth, db } from '../firebase';

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

function getNameFromEmail(email) {
    if (!email || typeof email !== 'string') {
        return '';
    }

    const [prefix] = email.split('@');
    return prefix || '';
}

async function resolveProfileName(user) {
    const fromDisplayName = String(user?.displayName || '').trim();
    if (fromDisplayName) {
        return fromDisplayName;
    }

    try {
        const snapshot = await getDoc(doc(db, 'users', user.uid));
        const firestoreName = String(snapshot.data()?.name || '').trim();
        if (firestoreName) {
            return firestoreName;
        }
    } catch {
        // Use other fallback sources when Firestore read fails.
    }

    return getNameFromEmail(user?.email);
}

function initProfileName() {
    // Keep server-rendered full name as initial fallback.
    setProfileName(getFallbackName());

    onAuthStateChanged(auth, async (user) => {
        if (!user) return;

        const nameFromFirebase = await resolveProfileName(user);
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
