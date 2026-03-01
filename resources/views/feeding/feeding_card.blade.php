<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2 sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Feeding Schedule</h2>
            <p class="text-sm text-slate-600">All saved feeding schedules with batch, pen, type, date, time, quantity, and status.</p>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table class="min-w-[960px] w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold">Feeding ID</th>
                    <th class="px-4 py-3 font-semibold">Batch ID</th>
                    <th class="px-4 py-3 font-semibold">Assigned Pen</th>
                    <th class="px-4 py-3 font-semibold">Feeding Type</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Time</th>
                    <th class="px-4 py-3 font-semibold">Quantity</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody id="feeding-schedules-body" class="divide-y divide-slate-100 bg-white">
                @for ($index = 0; $index < 5; $index++)
                    <tr data-feeding-skeleton="1" class="animate-pulse">
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-20 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-20 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-24 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-14 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-6 w-20 rounded-full bg-slate-200"></div></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const feedingSchedulesBody = document.getElementById('feeding-schedules-body');
        if (!feedingSchedulesBody || feedingSchedulesBody.dataset.bound === '1') {
            return;
        }

        feedingSchedulesBody.dataset.bound = '1';

        const schedulesApiUrl = @js(route('feeding.schedules.index'));

        const escapeHtml = function (value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const renderStatusBadge = function (status) {
            if (status === 'Completed') {
                return '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Completed</span>';
            }

            if (status === 'Pending') {
                return '<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Pending</span>';
            }

            return '<span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Delayed</span>';
        };

        const buildRowMarkup = function (feedingCard) {
            const batchName = feedingCard.batch_name
                ? `<p class="text-xs text-slate-500">${escapeHtml(feedingCard.batch_name)}</p>`
                : '';

            return `
                <tr class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                    <td class="px-4 py-3 font-semibold text-slate-900">${escapeHtml(feedingCard.feeding_id)}</td>
                    <td class="px-4 py-3 text-slate-700">
                        <p class="font-medium text-slate-900">${escapeHtml(feedingCard.batch_id)}</p>
                        ${batchName}
                    </td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(feedingCard.pen_id ?? 'N/A')}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(feedingCard.feeding_type)}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(feedingCard.feeding_date)}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(feedingCard.feeding_time)}</td>
                    <td class="px-4 py-3 text-slate-600">${escapeHtml(feedingCard.feeding_quantity_kg)}</td>
                    <td class="px-4 py-3">${renderStatusBadge(String(feedingCard.status ?? 'Delayed'))}</td>
                </tr>
            `;
        };

        const renderMessageRow = function (message) {
            feedingSchedulesBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">${escapeHtml(message)}</td>
                </tr>
            `;
        };

        fetch(schedulesApiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return {
                        ok: response.ok,
                        payload: payload,
                    };
                });
            })
            .then(function (result) {
                const feedingCards = Array.isArray(result.payload?.data) ? result.payload.data : [];
                if (feedingCards.length === 0) {
                    renderMessageRow('No feeding schedules found. Add a schedule to populate this card.');
                    return;
                }

                feedingSchedulesBody.innerHTML = feedingCards.map(buildRowMarkup).join('');
            })
            .catch(function () {
                renderMessageRow('Unable to load feeding schedules right now. Please refresh this page.');
            });
    });
</script>
