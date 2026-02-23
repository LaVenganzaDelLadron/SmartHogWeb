import {
    createUserWithEmailAndPassword,
    signInWithEmailAndPassword,
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

export async function signupUser({ name, email, password, confirmPassword }) {
    if (!name || !email || !password || !confirmPassword) {
        throw new Error('Please complete all fields.');
    }

    if (password !== confirmPassword) {
        throw new Error('Password confirmation does not match.');
    }

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
}

export async function loginUser({ email, password }) {
    if (!email || !password) {
        throw new Error('Please enter both email and password.');
    }

    const userCredential = await signInWithEmailAndPassword(auth, email, password);
    return userCredential.user;
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


if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        bindSignupForm();
        bindLoginForm();
    });
} else {
    bindSignupForm();
    bindLoginForm();
}
