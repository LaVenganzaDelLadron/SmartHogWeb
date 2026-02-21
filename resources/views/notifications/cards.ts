type NotificationStatus = 'new' | 'read';

type NotificationCard = {
    type: string;
    event: string;
    message: string;
    time: string;
    status: NotificationStatus;
};

const eventIcons: Record<string, string[]> = {
    approved: ['M4 10 8 14 16 6'],
    rejected: ['M6 6l8 8', 'M14 6l-8 8'],
    completed: ['M4 10 8 14 16 6'],
    delayed: ['M10 5v5l3 2'],
    'system alert': ['M10 3.5 16.5 15h-13z', 'M10 7.6v3.5', 'M10 13.5h.01'],
    warning: ['M10 3.5 16.5 15h-13z', 'M10 7.6v3.5', 'M10 13.5h.01'],
};

function parseNotificationTime(value: string): Date {
    return new Date(value.replace(' ', 'T'));
}

function formatNotificationTime(value: string): string {
    const date = parseNotificationTime(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return date.toLocaleString('en-US', {
        month: 'short',
        day: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    }).replace(',', ' \u00b7');
}

function toDateKey(value: string): string {
    const date = parseNotificationTime(value);
    if (Number.isNaN(date.getTime())) {
        return '';
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function toTimestamp(value: string): string {
    const date = parseNotificationTime(value);
    return Number.isNaN(date.getTime()) ? '0' : String(Math.floor(date.getTime() / 1000));
}

function toTitleCase(value: string): string {
    return value.replace(/\b\w/g, (char) => char.toUpperCase());
}

function setRead(card: HTMLElement): void {
    if (card.dataset.status === 'read') {
        return;
    }

    card.dataset.status = 'read';
    card.classList.remove('border-cyan-200', 'bg-cyan-50/40');
    card.classList.add('border-slate-200', 'bg-slate-50/40', 'opacity-90');

    const statusChip = card.querySelector<HTMLElement>('[data-status-chip]');
    if (statusChip) {
        statusChip.textContent = 'Read';
        statusChip.className = 'rounded-full px-2 py-0.5 text-[11px] font-semibold bg-slate-200 text-slate-600';
        statusChip.dataset.statusChip = '1';
    }

    const ackButton = card.querySelector<HTMLButtonElement>('.ack-btn');
    if (ackButton) {
        ackButton.disabled = true;
        ackButton.classList.add('opacity-60', 'cursor-not-allowed');
    }
}

function applyFilters(
    list: HTMLElement,
    filterType: HTMLSelectElement | null,
    filterStatus: HTMLSelectElement | null,
    filterDate: HTMLInputElement | null,
    sortOrder: HTMLSelectElement | null
): void {
    const items = Array.from(list.querySelectorAll<HTMLElement>('.notification-item'));
    const typeVal = filterType?.value || 'all';
    const statusVal = filterStatus?.value || 'all';
    const dateVal = filterDate?.value || '';
    const sortVal = sortOrder?.value || 'latest';

    items.forEach((item) => {
        const passType = typeVal === 'all' || item.dataset.type === typeVal;
        const passStatus = statusVal === 'all' || item.dataset.status === statusVal;
        const passDate = !dateVal || item.dataset.date === dateVal;
        item.style.display = passType && passStatus && passDate ? '' : 'none';
    });

    const visible = items.filter((item) => item.style.display !== 'none');
    visible.sort((first, second) => {
        const firstTime = Number(first.dataset.time || 0);
        const secondTime = Number(second.dataset.time || 0);
        return sortVal === 'oldest' ? firstTime - secondTime : secondTime - firstTime;
    });

    visible.forEach((item, index) => {
        list.appendChild(item);
        const timelineLine = item.querySelector<HTMLElement>('[data-timeline-line]');
        if (timelineLine) {
            timelineLine.classList.toggle('opacity-0', index === visible.length - 1);
        }
    });
}

function createCard(notification: NotificationCard): HTMLElement {
    const event = notification.event.toLowerCase();
    const isPositive = event === 'approved' || event === 'completed';
    const isNegative = event === 'rejected' || event === 'system alert';
    const iconWrapClass = isPositive
        ? 'bg-emerald-100 text-emerald-700'
        : (isNegative ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800');
    const statusClass = notification.status === 'new'
        ? 'bg-cyan-100 text-cyan-700'
        : 'bg-slate-200 text-slate-600';
    const cardStateClass = notification.status === 'new'
        ? 'border-cyan-200 bg-cyan-50/40'
        : 'border-slate-200 bg-slate-50/40 opacity-90';

    const card = document.createElement('article');
    card.className = `notification-item relative rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md ${cardStateClass}`;
    card.dataset.type = notification.type;
    card.dataset.status = notification.status;
    card.dataset.date = toDateKey(notification.time);
    card.dataset.time = toTimestamp(notification.time);

    const iconPaths = eventIcons[event] ?? ['M10 3.5 16.5 15h-13z'];
    const paths = iconPaths
        .map((path) => `<path stroke-linecap="round" stroke-linejoin="round" d="${path}" />`)
        .join('');

    card.innerHTML = `
        <div data-timeline-line class="absolute bottom-0 left-6 top-0 hidden w-px bg-slate-200/70 md:block"></div>
        <div class="relative flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full ${iconWrapClass}">
                    <svg viewBox="0 0 20 20" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        ${paths}
                    </svg>
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-sm font-semibold text-slate-900">${toTitleCase(notification.event)}</p>
                        <span data-status-chip="1" class="rounded-full px-2 py-0.5 text-[11px] font-semibold ${statusClass}">${toTitleCase(notification.status)}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">${toTitleCase(notification.type.replace('-', ' '))}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate-700"></p>
                    <p class="mt-2 text-xs text-slate-500">${formatNotificationTime(notification.time)}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
                <button type="button" class="rounded-md border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-white">View Details</button>
                <button type="button" class="ack-btn rounded-md border border-cyan-200 px-2.5 py-1 text-xs font-medium text-cyan-700 hover:bg-cyan-50">Acknowledge</button>
                <button type="button" class="rounded-md border border-emerald-200 px-2.5 py-1 text-xs font-medium text-emerald-700 hover:bg-emerald-50">Resolve</button>
            </div>
        </div>
    `;

    const message = card.querySelector('p.mt-1');
    if (message) {
        message.textContent = notification.message;
    }

    if (notification.status === 'read') {
        const ackButton = card.querySelector<HTMLButtonElement>('.ack-btn');
        if (ackButton) {
            ackButton.disabled = true;
            ackButton.classList.add('opacity-60', 'cursor-not-allowed');
        }
    }

    return card;
}

function parseNotifications(): NotificationCard[] {
    const dataElement = document.getElementById('notifications-data');
    if (!dataElement?.textContent) {
        return [];
    }

    try {
        const parsed = JSON.parse(dataElement.textContent) as NotificationCard[];
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return [];
    }
}

function initNotificationCards(): void {
    const list = document.getElementById('notifications-list');
    if (!list) {
        return;
    }

    const notifications = parseNotifications();
    list.innerHTML = '';
    notifications.forEach((notification) => {
        list.appendChild(createCard(notification));
    });

    const filterType = document.getElementById('filter-type') as HTMLSelectElement | null;
    const filterStatus = document.getElementById('filter-status') as HTMLSelectElement | null;
    const filterDate = document.getElementById('filter-date') as HTMLInputElement | null;
    const sortOrder = document.getElementById('sort-order') as HTMLSelectElement | null;
    const markAllRead = document.getElementById('mark-all-read') as HTMLButtonElement | null;

    const refresh = () => applyFilters(list, filterType, filterStatus, filterDate, sortOrder);

    [filterType, filterStatus, filterDate, sortOrder].forEach((element) => {
        element?.addEventListener('change', refresh);
        element?.addEventListener('input', refresh);
    });

    list.addEventListener('click', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement)) {
            return;
        }

        if (target.classList.contains('ack-btn')) {
            const card = target.closest<HTMLElement>('.notification-item');
            if (!card) {
                return;
            }
            setRead(card);
            refresh();
        }
    });

    markAllRead?.addEventListener('click', () => {
        list.querySelectorAll<HTMLElement>('.notification-item').forEach((item) => setRead(item));
        refresh();
    });

    refresh();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initNotificationCards);
} else {
    initNotificationCards();
}
