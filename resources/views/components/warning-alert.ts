export type WarningAlertOptions = {
    title: string;
    message: string;
    durationMs?: number;
    container?: HTMLElement | null;
    dismissible?: boolean;
};

const ALERT_ROOT_ID = 'smart-hog-warning-alert-root';

function getRoot(container?: HTMLElement | null): HTMLElement {
    if (container) {
        let localRoot = container.querySelector<HTMLElement>(`#${ALERT_ROOT_ID}`);
        if (!localRoot) {
            localRoot = document.createElement('div');
            localRoot.id = ALERT_ROOT_ID;
            localRoot.style.position = 'absolute';
            localRoot.style.top = '16px';
            localRoot.style.right = '16px';
            localRoot.style.zIndex = '60';
            localRoot.style.width = 'min(380px, calc(100vw - 32px))';
            container.appendChild(localRoot);
        }
        return localRoot;
    }

    let root = document.getElementById(ALERT_ROOT_ID);
    if (!root) {
        root = document.createElement('div');
        root.id = ALERT_ROOT_ID;
        root.style.position = 'fixed';
        root.style.top = '16px';
        root.style.right = '16px';
        root.style.zIndex = '9999';
        root.style.width = 'min(380px, calc(100vw - 32px))';
        document.body.appendChild(root);
    }
    return root;
}

function createAlert(options: WarningAlertOptions): HTMLDivElement {
    const alert = document.createElement('div');
    alert.setAttribute('role', 'alert');
    alert.setAttribute('aria-live', 'assertive');
    alert.style.background = '#ffffff';
    alert.style.border = '1px solid #fde68a';
    alert.style.borderRadius = '14px';
    alert.style.boxShadow = '0 12px 30px rgba(2, 6, 23, 0.12)';
    alert.style.overflow = 'hidden';
    alert.style.transform = 'translateY(-8px)';
    alert.style.opacity = '0';
    alert.style.transition = 'opacity 180ms ease, transform 180ms ease';
    alert.style.marginBottom = '10px';

    const progress = document.createElement('div');
    progress.style.height = '4px';
    progress.style.background = 'linear-gradient(90deg, #d97706, #f59e0b)';

    const body = document.createElement('div');
    body.style.display = 'flex';
    body.style.alignItems = 'flex-start';
    body.style.gap = '10px';
    body.style.padding = '12px 14px';

    const iconWrap = document.createElement('div');
    iconWrap.style.width = '28px';
    iconWrap.style.height = '28px';
    iconWrap.style.borderRadius = '999px';
    iconWrap.style.background = '#fef3c7';
    iconWrap.style.color = '#92400e';
    iconWrap.style.display = 'inline-flex';
    iconWrap.style.alignItems = 'center';
    iconWrap.style.justifyContent = 'center';
    iconWrap.style.flexShrink = '0';
    iconWrap.innerHTML =
        '<svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 6.8v3.5m0 2.2h.01M10 3.5 16.5 15h-13z" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    const textWrap = document.createElement('div');
    textWrap.style.flex = '1';

    const title = document.createElement('p');
    title.textContent = options.title;
    title.style.margin = '0';
    title.style.fontSize = '14px';
    title.style.fontWeight = '700';
    title.style.color = '#78350f';

    const message = document.createElement('p');
    message.textContent = options.message;
    message.style.margin = '4px 0 0';
    message.style.fontSize = '13px';
    message.style.lineHeight = '1.45';
    message.style.color = '#334155';

    textWrap.appendChild(title);
    textWrap.appendChild(message);

    body.appendChild(iconWrap);
    body.appendChild(textWrap);

    if (options.dismissible !== false) {
        const close = document.createElement('button');
        close.type = 'button';
        close.setAttribute('aria-label', 'Dismiss alert');
        close.textContent = 'x';
        close.style.border = '0';
        close.style.background = 'transparent';
        close.style.color = '#64748b';
        close.style.fontSize = '16px';
        close.style.lineHeight = '1';
        close.style.cursor = 'pointer';
        close.style.padding = '2px 4px';
        close.style.borderRadius = '6px';
        close.onclick = () => removeAlert(alert);
        body.appendChild(close);
    }

    alert.appendChild(progress);
    alert.appendChild(body);

    return alert;
}

function removeAlert(alert: HTMLElement): void {
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(-8px)';
    window.setTimeout(() => {
        alert.remove();
    }, 180);
}

export function showWarningAlert(options: WarningAlertOptions): void {
    const root = getRoot(options.container);
    const alert = createAlert(options);
    root.prepend(alert);

    requestAnimationFrame(() => {
        alert.style.opacity = '1';
        alert.style.transform = 'translateY(0)';
    });

    const duration = options.durationMs ?? 3500;
    if (duration > 0) {
        window.setTimeout(() => removeAlert(alert), duration);
    }
}

declare global {
    interface Window {
        showWarningAlert: (options: WarningAlertOptions) => void;
    }
}

window.showWarningAlert = showWarningAlert;
