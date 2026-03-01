import {
    createUserWithEmailAndPassword,
    deleteUser,
    signInWithEmailAndPassword,
    signOut,
    updateProfile,
} from 'firebase/auth';
import { doc, serverTimestamp, setDoc } from 'firebase/firestore';
import { auth, db } from '../firebase';

function setFormError(elementId, message) {
    const errorBox = document.getElementById(elementId);
    if (!errorBox) return;

    if (!message) {
        errorBox.classList.add('hidden');
        errorBox.textContent = '';
        return;
    }

    errorBox.textContent = message;
    errorBox.classList.remove('hidden');
}

function mapSignupError(code) {
    switch (code) {
        case 'auth/email-already-in-use':
            return 'This email is already registered.';
        case 'auth/invalid-email':
            return 'Please enter a valid email address.';
        case 'auth/weak-password':
            return 'Password is too weak. Use at least 6 characters.';
        default:
            return 'Signup failed. Please try again.';
    }
}

function mapLoginError(code) {
    switch (code) {
        case 'auth/user-not-found':
        case 'auth/wrong-password':
        case 'auth/invalid-credential':
            return 'Invalid email or password.';
        case 'auth/invalid-email':
            return 'Please enter a valid email address.';
        case 'auth/too-many-requests':
            return 'Too many attempts. Please try again later.';
        default:
            return 'Login failed. Please try again.';
    }
}

function isCredentialError(code, message) {
    if (code === 'auth/user-not-found' || code === 'auth/wrong-password' || code === 'auth/invalid-credential') {
        return true;
    }

    const normalizedMessage = String(message || '').toLowerCase();
    return normalizedMessage.includes('invalid username or password') || normalizedMessage.includes('invalid email or password');
}

function normalizeApiError(payload, fallbackMessage) {
    if (!payload || typeof payload !== 'object') {
        return fallbackMessage;
    }

    if (typeof payload.message === 'string' && payload.message.trim()) {
        return payload.message.trim();
    }

    const firstKey = Object.keys(payload)[0];
    const firstValue = firstKey ? payload[firstKey] : null;

    if (Array.isArray(firstValue) && firstValue[0]) {
        return String(firstValue[0]);
    }

    if (typeof firstValue === 'string' && firstValue.trim()) {
        return firstValue.trim();
    }

    return fallbackMessage;
}

function getCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) {
        return metaToken;
    }

    const inputToken = document.querySelector('input[name="_token"]')?.value;
    return inputToken || '';
}

async function callLaravelAuth(endpoint, payload, fallbackMessage) {
    const csrfToken = getCsrfToken();
    const response = await fetch(endpoint, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(payload),
    });

    let data = null;
    try {
        data = await response.json();
    } catch {
        data = null;
    }

    if (!response.ok) {
        throw new Error(normalizeApiError(data, fallbackMessage));
    }

    return data;
}

export async function signupUser({ name, email, password, confirmPassword }) {
    if (!name || !email || !password || !confirmPassword) {
        throw new Error('Please complete all fields.');
    }

    if (password !== confirmPassword) {
        throw new Error('Password confirmation does not match.');
    }

    const firebaseSignupPromise = (async () => {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        await updateProfile(user, { displayName: name });

        await setDoc(doc(db, 'users', user.uid), {
            uid: user.uid,
            name,
            email,
            createdAt: serverTimestamp(),
            updatedAt: serverTimestamp(),
            source: 'web-signup',
        });

        return user;
    })();

    const apiSignupPromise = callLaravelAuth('/api/signup', {
        name,
        email,
        password,
        password_confirmation: confirmPassword,
    }, 'Signup failed. Please try again.');

    const [firebaseResult, apiResult] = await Promise.allSettled([firebaseSignupPromise, apiSignupPromise]);

    if (firebaseResult.status === 'fulfilled' && apiResult.status === 'fulfilled') {
        return firebaseResult.value;
    }

    if (firebaseResult.status === 'fulfilled' && apiResult.status === 'rejected') {
        await deleteUser(firebaseResult.value).catch(() => {});
        throw apiResult.reason;
    }

    if (firebaseResult.status === 'rejected') {
        throw firebaseResult.reason;
    }

    throw new Error('Signup failed. Please try again.');
}

export async function loginUser({ email, password }) {
    if (!email || !password) {
        throw new Error('Please enter both email and password.');
    }

    const firebaseLoginPromise = signInWithEmailAndPassword(auth, email, password);
    const apiLoginPromise = callLaravelAuth('/api/login', {
        email,
        password,
    }, 'Invalid email or password.');

    const [firebaseResult, apiResult] = await Promise.allSettled([firebaseLoginPromise, apiLoginPromise]);

    if (firebaseResult.status === 'fulfilled' && apiResult.status === 'fulfilled') {
        return firebaseResult.value.user;
    }

    if (firebaseResult.status === 'fulfilled' && apiResult.status === 'rejected') {
        await signOut(auth).catch(() => {});
        throw apiResult.reason;
    }

    if (firebaseResult.status === 'rejected') {
        throw firebaseResult.reason;
    }

    throw new Error('Login failed. Please try again.');
}

function bindSignupForm() {
    const form = document.getElementById('signupForm');
    if (!form || form.dataset.firebaseBound === '1') return;
    if (form.dataset.firebaseAuth !== 'true') return;

    form.dataset.firebaseBound = '1';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setFormError('signupError', '');

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalLabel = submitBtn ? submitBtn.textContent : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating Account...';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        }

        try {
            const formData = new FormData(form);
            const payload = {
                name: String(formData.get('name') || '').trim(),
                email: String(formData.get('email') || '').trim(),
                password: String(formData.get('password') || ''),
                password_confirmation: String(formData.get('password_confirmation') || ''),
            };

            await signupUser({
                name: payload.name,
                email: payload.email,
                password: payload.password,
                confirmPassword: payload.password_confirmation,
            });

            if (typeof window.showSuccessAlert === 'function') {
                window.showSuccessAlert({
                    title: 'Account Created',
                    message: 'Your account is ready. Please login.',
                    durationMs: 2200,
                });
            }

            window.setTimeout(() => {
                window.location.href = '/login';
            }, 500);
        } catch (error) {
            const code = error && typeof error === 'object' ? error.code : null;
            const fallback = error instanceof Error ? error.message : 'Signup failed. Please try again.';
            setFormError('signupError', code ? mapSignupError(code) : fallback);
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalLabel;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        }
    });
}

function bindLoginForm() {
    const form = document.getElementById('loginForm');
    if (!form || form.dataset.firebaseBound === '1') return;
    if (form.dataset.firebaseAuth !== 'true') return;

    form.dataset.firebaseBound = '1';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setFormError('loginError', '');

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalLabel = submitBtn ? submitBtn.textContent : '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing In...';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        }

        try {
            const formData = new FormData(form);
            const payload = {
                email: String(formData.get('email') || '').trim(),
                password: String(formData.get('password') || ''),
            };

            await loginUser({
                email: payload.email,
                password: payload.password,
            });

            if (typeof window.showSuccessAlert === 'function') {
                window.showSuccessAlert({
                    title: 'Login Successful',
                    message: 'Welcome back to SMART-HOG.',
                    durationMs: 1700,
                });
            }

            window.setTimeout(() => {
                window.location.href = '/home';
            }, 350);
        } catch (error) {
            const code = error && typeof error === 'object' ? error.code : null;
            const fallback = error instanceof Error ? error.message : 'Login failed. Please try again.';
            const alertMessage = code ? mapLoginError(code) : fallback;
            const title = isCredentialError(code, fallback) ? 'Wrong Credentials' : 'Login Failed';

            setFormError('loginError', alertMessage);

            if (typeof window.showWarningAlert === 'function') {
                window.showWarningAlert({
                    title,
                    message: alertMessage,
                    durationMs: 3200,
                });
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalLabel;
                submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        }
    });
}

function bindLogoutForms() {
    const forms = document.querySelectorAll('form.js-firebase-logout');
    if (!forms.length) {
        return;
    }

    forms.forEach((form) => {
        if (form.dataset.firebaseBound === '1') {
            return;
        }

        form.dataset.firebaseBound = '1';

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn instanceof HTMLButtonElement) {
                submitBtn.disabled = true;
            }

            try {
                await signOut(auth);
            } catch {
                // Continue with Laravel logout even if Firebase sign out fails.
            }

            form.submit();
        });
    });
}


if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        bindSignupForm();
        bindLoginForm();
        bindLogoutForms();
    });
} else {
    bindSignupForm();
    bindLoginForm();
    bindLogoutForms();
}
