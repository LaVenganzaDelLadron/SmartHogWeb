const listElement = document.getElementById('notifications-list');
const notificationsStorageKey = 'smart_hog_frontend_notifications';

function readStoredNotifications() {
    return JSON.parse(window.localStorage.getItem(notificationsStorageKey) ?? '[]');
}

function writeStoredNotifications(notifications) {
    window.localStorage.setItem(notificationsStorageKey, JSON.stringify(notifications));
}

function toTitleCase(value) {
    return value.replace(/\b\w/g, (char) => char.toUpperCase());
}

function formatTime(value) {
    const date = new Date(String(value).replace(' ', 'T'));
    if (Number.isNaN(date.getTime())) {
        return String(value);
    }

    return date.toLocaleString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    }).replace(',', ' ·');
}

function buildNotificationCard(notification) {
    const title = String(notification.title ?? 'Notification');
    const event = title.toLowerCase();
    const status = String(notification.status ?? 'read').toLowerCase();
    const type = String(notification.type ?? 'system').toLowerCase();
    const description = String(notification.description ?? '');
    const id = String(notification.id ?? '');

    const isPositive = ['approved', 'completed'].includes(event);
    const isNegative = ['rejected', 'system alert'].includes(event);

    const iconWrapClass = isPositive
        ? 'bg-emerald-100 text-emerald-700'
        : (isNegative ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800');

    const statusClass = status === 'new'
        ? 'bg-cyan-100 text-cyan-700'
        : 'bg-slate-200 text-slate-600';

    const cardStateClass = status === 'new'
        ? 'border-cyan-200 bg-cyan-50/40'
        : 'border-slate-200 bg-slate-50/40 opacity-90';

    const typeLabel = toTitleCase(type.replace(/[-_]/g, ' '));

    const article = document.createElement('article');
    article.className = `notification-item relative rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md ${cardStateClass}`;
    article.dataset.id = id;
    article.dataset.type = type;
    article.dataset.status = status;

    article.innerHTML = `
        <div class="absolute bottom-0 left-6 top-0 hidden w-px bg-slate-200/70 md:block"></div>
        <div class="relative flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full ${iconWrapClass}">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 3.5 16.5 15h-13z" />
                    </svg>
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-semibold text-slate-900">${toTitleCase(title)}</p>
                        <span data-status-chip="1" class="rounded-full px-2 py-0.5 text-[11px] font-semibold ${statusClass}">${toTitleCase(status)}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">${typeLabel}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700">${description}</p>
                    <p class="mt-2 text-xs text-slate-500">${formatTime(notification.recorded_date)}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
                <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-white">View Details</button>
                <button type="button" class="ack-btn rounded-md border border-cyan-200 px-2.5 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-50 ${status === 'read' ? 'opacity-60 cursor-not-allowed' : ''}" ${status === 'read' ? 'disabled' : ''}>Acknowledge</button>
                <button type="button" class="rounded-md border border-emerald-200 px-2.5 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50">Resolve</button>
            </div>
        </div>
    `;

    return article;
}

function markNotificationAsRead(card) {
    const id = card.dataset.id;
    if (!id) {
        return;
    }

    const notifications = readStoredNotifications();
    const updatedNotifications = notifications.map((notification) => {
        if (String(notification.id) === id) {
            return {
                ...notification,
                status: 'read',
            };
        }

        return notification;
    });

    writeStoredNotifications(updatedNotifications);

    card.dataset.status = 'read';
    card.classList.remove('border-cyan-200', 'bg-cyan-50/40');
    card.classList.add('border-slate-200', 'bg-slate-50/40', 'opacity-90');

    const chip = card.querySelector('[data-status-chip]');
    if (chip) {
        chip.textContent = 'Read';
        chip.className = 'rounded-full px-2 py-0.5 text-[11px] font-semibold bg-slate-200 text-slate-600';
    }

    const ackButton = card.querySelector('.ack-btn');
    if (ackButton instanceof HTMLButtonElement) {
        ackButton.disabled = true;
        ackButton.classList.add('opacity-60', 'cursor-not-allowed');
    }
}

async function receiveNotification(payload) {
    const notification = {
        id: `NTF-${Date.now()}`,
        title: String(payload?.title ?? 'Notification'),
        status: String(payload?.status ?? 'new'),
        type: String(payload?.type ?? 'system'),
        description: String(payload?.description ?? ''),
        recorded_date: String(payload?.recorded_date ?? new Date().toISOString()),
    };

    const notifications = readStoredNotifications();
    notifications.unshift(notification);
    writeStoredNotifications(notifications);

    if (listElement) {
        const card = buildNotificationCard(notification);
        listElement.prepend(card);
    }

    return {
        ok: true,
        notification,
    };
}

function initNotificationActions() {
    if (!listElement) {
        return;
    }

    const storedNotifications = readStoredNotifications();
    if (storedNotifications.length > 0) {
        listElement.innerHTML = '';
        storedNotifications.forEach((notification) => {
            listElement.append(buildNotificationCard(notification));
        });
    }

    listElement.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement) || !target.classList.contains('ack-btn')) {
            return;
        }

        const card = target.closest('.notification-item');
        if (!(card instanceof HTMLElement)) {
            return;
        }

        markNotificationAsRead(card);
    });

    const markAllButton = document.getElementById('mark-all-read');
    markAllButton?.addEventListener('click', () => {
        const cards = Array.from(listElement.querySelectorAll('.notification-item'));
        cards.forEach((card) => {
            if (card instanceof HTMLElement && card.dataset.status === 'new') {
                markNotificationAsRead(card);
            }
        });
    });
}

window.receiveNotification = receiveNotification;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNotificationActions);
} else {
    initNotificationActions();
}
