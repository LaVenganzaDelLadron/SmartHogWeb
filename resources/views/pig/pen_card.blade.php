<article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Pen Records</h2>
            <p class="text-sm text-slate-600">List of all pens with capacity, status, notes, and quick actions.</p>
        </div>
    </div>

    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        <div>
            <label for="pen-search-input" class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Search Pen</label>
            <input
                id="pen-search-input"
                type="search"
                placeholder="Search by pen id, name, or notes..."
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
            >
        </div>
        <div>
            <label for="pen-status-filter" class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500">Filter Status</label>
            <select
                id="pen-status-filter"
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
            >
                <option value="">All Statuses</option>
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-slate-200">
        <table id="pen-records-table" class="min-w-[880px] w-full text-left text-sm">
            <thead class="sticky top-0 bg-slate-50 text-xs uppercase tracking-[0.08em] text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen ID</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Pen Name</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Capacity</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Status</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Notes</th>
                    <th class="px-4 py-3 font-semibold text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="pen-records-body" class="divide-y divide-slate-100 bg-white">
                @for ($index = 0; $index < 5; $index++)
                    <tr data-pen-skeleton="1" class="animate-pulse">
                        <td class="px-4 py-3"><div class="h-4 w-16 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-24 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-12 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-6 w-20 rounded-full bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-4 w-40 rounded bg-slate-200"></div></td>
                        <td class="px-4 py-3"><div class="h-8 w-20 rounded bg-slate-200"></div></td>
                    </tr>
                @endfor
                <tr id="pen-no-results-row" class="hidden">
                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No matching pen records found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('pen-records-body');
        const noResultsRow = document.getElementById('pen-no-results-row');
        const searchInput = document.getElementById('pen-search-input');
        const statusFilter = document.getElementById('pen-status-filter');
        const deleteModal = document.getElementById('pig-delete-pen-modal');
        const deleteModalCode = document.getElementById('delete-pen-code');
        const deleteModalName = document.getElementById('delete-pen-name');
        const deleteModalCapacity = document.getElementById('delete-pen-capacity');
        const deleteModalConfirmButton = document.getElementById('confirm-delete-pen-button');
        const editModal = document.getElementById('pig-edit-pen-modal');
        const editModalCodePreview = document.getElementById('update-pen-code-preview');
        const editModalStatusPreview = document.getElementById('update-pen-status-preview');
        const editModalCapacityPreview = document.getElementById('update-pen-capacity-preview');
        const editForm = document.getElementById('pig-update-pen-form');
        const editPenNameInput = document.getElementById('update-pen-name');
        const editPenCapacityInput = document.getElementById('update-pen-capacity');
        const editPenStatusInput = document.getElementById('update-pen-status');
        const editPenNotesInput = document.getElementById('update-pen-notes');
        const editModalConfirmButton = document.getElementById('confirm-update-pen-button');

        if (! tableBody || ! noResultsRow || ! searchInput || ! statusFilter || tableBody.dataset.bound === '1') {
            return;
        }

        tableBody.dataset.bound = '1';

        const endpoint = '{{ route('api.pens.all') }}';
        const deleteEndpointTemplate = '{{ route('api.pens.delete', ['pen_code' => '__PEN_CODE__']) }}';
        const updateEndpointTemplate = '{{ route('api.pens.update', ['pen_code' => '__PEN_CODE__']) }}';
        const csrfToken = '{{ csrf_token() }}';
        let penItems = [];
        let pendingDeletePenCode = '';
        let pendingEditPenCode = '';

        const escapeHtml = function (value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        };

        const statusBadge = function (status) {
            const normalizedStatus = String(status ?? '').toLowerCase();
            if (normalizedStatus === 'occupied') {
                return '<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">Occupied</span>';
            }

            if (normalizedStatus === 'maintenance') {
                return '<span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">Maintenance</span>';
            }

            return '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Available</span>';
        };

        const renderRows = function (items) {
            const rowsMarkup = items.map(function (item) {
                const penCode = String(item.pen_code ?? 'N/A');
                const penName = String(item.pen_name ?? 'N/A');
                const capacity = Number(item.capacity ?? 0);
                const status = String(item.status ?? 'available');
                const notes = String(item.notes ?? 'No notes');

                return `
                    <tr class="odd:bg-white even:bg-slate-50/40 hover:bg-slate-50/80">
                        <td class="px-4 py-3 font-semibold text-slate-900">${escapeHtml(penCode)}</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(penName)}</td>
                        <td class="px-4 py-3 text-slate-700">${escapeHtml(capacity)}</td>
                        <td class="px-4 py-3">${statusBadge(status)}</td>
                        <td class="px-4 py-3 text-slate-600">${escapeHtml(notes)}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button" data-edit-pen="${escapeHtml(penCode)}" data-edit-pen-name="${escapeHtml(penName)}" data-edit-pen-capacity="${escapeHtml(capacity)}" data-edit-pen-status="${escapeHtml(status)}" data-edit-pen-notes="${escapeHtml(notes)}" class="inline-flex items-center rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">Edit</button>
                                <button type="button" data-delete-pen="${escapeHtml(penCode)}" data-delete-pen-name="${escapeHtml(penName)}" data-delete-pen-capacity="${escapeHtml(capacity)}" class="inline-flex items-center rounded-lg border border-rose-200 px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-50">Delete</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            tableBody.innerHTML = rowsMarkup + noResultsRow.outerHTML;
        };

        const applyFilters = function () {
            const query = String(searchInput.value ?? '').trim().toLowerCase();
            const selectedStatus = String(statusFilter.value ?? '').trim().toLowerCase();

            const filtered = penItems.filter(function (item) {
                const itemStatus = String(item.status ?? '').toLowerCase();
                const matchesStatus = selectedStatus === '' || itemStatus === selectedStatus;

                const searchable = [
                    item.pen_code,
                    item.pen_name,
                    item.notes,
                ].map(function (value) {
                    return String(value ?? '').toLowerCase();
                }).join(' ');

                const matchesQuery = query === '' || searchable.includes(query);

                return matchesStatus && matchesQuery;
            });

            renderRows(filtered);

            const refreshedNoResultsRow = document.getElementById('pen-no-results-row');
            if (refreshedNoResultsRow) {
                refreshedNoResultsRow.classList.toggle('hidden', filtered.length !== 0);
            }
        };

        const deletePenRecord = function (penCode) {
            if (!penCode) {
                return Promise.resolve(false);
            }

            const deleteUrl = deleteEndpointTemplate.replace('__PEN_CODE__', encodeURIComponent(penCode));

            return fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
            })
                .then(function (response) {
                    return response.json().then(function (payload) {
                        return { ok: response.ok, payload: payload };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.payload?.ok) {
                        const message = typeof result.payload?.message === 'string' && result.payload.message.trim() !== ''
                            ? result.payload.message
                            : 'Failed to remove pen. Please try again.';

                        if (typeof window.showWarningAlert === 'function') {
                            window.showWarningAlert({
                                title: 'Delete Failed',
                                message: message,
                                durationMs: 3200,
                            });
                        }
                        return false;
                    }

                    penItems = penItems.filter(function (item) {
                        return String(item.pen_code ?? '') !== penCode;
                    });
                    applyFilters();

                    if (typeof window.showSuccessAlert === 'function') {
                        window.showSuccessAlert({
                            title: 'Pen Deleted',
                            message: result.payload?.message || 'Pen removed successfully.',
                            durationMs: 2400,
                        });
                    }
                    return true;
                })
                .catch(function () {
                    if (typeof window.showWarningAlert === 'function') {
                        window.showWarningAlert({
                            title: 'Delete Failed',
                            message: 'Unable to remove pen right now. Please try again.',
                            durationMs: 3200,
                        });
                    }
                    return false;
                });
        };

        const updatePenRecord = function (penCode, payload) {
            if (!penCode) {
                return Promise.resolve(false);
            }

            const updateUrl = updateEndpointTemplate.replace('__PEN_CODE__', encodeURIComponent(penCode));

            return fetch(updateUrl, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload),
            })
                .then(function (response) {
                    return response.json().then(function (resultPayload) {
                        return { ok: response.ok, payload: resultPayload };
                    });
                })
                .then(function (result) {
                    if (!result.ok || !result.payload?.ok) {
                        const message = typeof result.payload?.message === 'string' && result.payload.message.trim() !== ''
                            ? result.payload.message
                            : 'Failed to update pen. Please try again.';

                        if (typeof window.showWarningAlert === 'function') {
                            window.showWarningAlert({
                                title: 'Update Failed',
                                message: message,
                                durationMs: 3200,
                            });
                        }
                        return false;
                    }

                    penItems = penItems.map(function (item) {
                        if (String(item.pen_code ?? '') !== penCode) {
                            return item;
                        }

                        return {
                            ...item,
                            pen_name: payload.pen_name,
                            capacity: payload.capacity,
                            status: payload.status,
                            notes: payload.notes,
                        };
                    });

                    applyFilters();

                    if (typeof window.showSuccessAlert === 'function') {
                        window.showSuccessAlert({
                            title: 'Pen Updated',
                            message: result.payload?.message || 'Pen updated successfully.',
                            durationMs: 2400,
                        });
                    }
                    return true;
                })
                .catch(function () {
                    if (typeof window.showWarningAlert === 'function') {
                        window.showWarningAlert({
                            title: 'Update Failed',
                            message: 'Unable to update pen right now. Please try again.',
                            durationMs: 3200,
                        });
                    }
                    return false;
                });
        };

        const closeDeleteModal = function () {
            if (! (deleteModal instanceof HTMLElement)) {
                return;
            }

            deleteModal.classList.add('hidden');
            deleteModal.setAttribute('aria-hidden', 'true');
            pendingDeletePenCode = '';
        };

        const closeEditModal = function () {
            if (! (editModal instanceof HTMLElement)) {
                return;
            }

            editModal.classList.add('hidden');
            editModal.setAttribute('aria-hidden', 'true');
            pendingEditPenCode = '';
        };

        const openDeleteModal = function (penCode, penName, penCapacity) {
            if (! (deleteModal instanceof HTMLElement)) {
                return;
            }

            pendingDeletePenCode = penCode;

            if (deleteModalCode instanceof HTMLElement) {
                deleteModalCode.textContent = penCode || 'N/A';
            }
            if (deleteModalName instanceof HTMLElement) {
                deleteModalName.textContent = penName || 'Unknown Pen';
            }
            if (deleteModalCapacity instanceof HTMLElement) {
                deleteModalCapacity.textContent = (penCapacity || '0') + ' pigs';
            }

            deleteModal.classList.remove('hidden');
            deleteModal.setAttribute('aria-hidden', 'false');
        };

        const openEditModal = function (penCode, penName, penCapacity, penStatus, penNotes) {
            if (! (editModal instanceof HTMLElement)) {
                return;
            }

            pendingEditPenCode = penCode;

            if (editModalCodePreview instanceof HTMLElement) {
                editModalCodePreview.textContent = penCode || 'N/A';
            }
            if (editModalStatusPreview instanceof HTMLElement) {
                editModalStatusPreview.textContent = penStatus || 'available';
            }
            if (editModalCapacityPreview instanceof HTMLElement) {
                editModalCapacityPreview.textContent = (penCapacity || '0') + ' pigs';
            }

            if (editPenNameInput instanceof HTMLInputElement) {
                editPenNameInput.value = penName || '';
            }
            if (editPenCapacityInput instanceof HTMLInputElement) {
                editPenCapacityInput.value = penCapacity || '0';
            }
            if (editPenStatusInput instanceof HTMLSelectElement) {
                editPenStatusInput.value = (penStatus || 'available').toLowerCase() === 'occupied' ? 'occupied' : 'available';
            }
            if (editPenNotesInput instanceof HTMLTextAreaElement) {
                editPenNotesInput.value = penNotes || '';
            }

            editModal.classList.remove('hidden');
            editModal.setAttribute('aria-hidden', 'false');
        };

        fetch(endpoint, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        })
            .then(function (response) {
                return response.json().then(function (payload) {
                    return { ok: response.ok, payload: payload };
                });
            })
            .then(function (result) {
                if (! result.ok || ! result.payload?.ok) {
                    throw new Error('Failed to load pen records.');
                }

                const source = result.payload?.data;
                const items = Array.isArray(source?.data) ? source.data : (Array.isArray(source) ? source : []);
                penItems = items;
                applyFilters();
            })
            .catch(function () {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-rose-700">Unable to load pen records right now. Please refresh the page.</td>
                    </tr>
                `;
            });

        searchInput.addEventListener('input', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
        tableBody.addEventListener('click', function (event) {
            const target = event.target;
            if (!(target instanceof HTMLElement)) {
                return;
            }

            const editButton = target.closest('[data-edit-pen]');
            if (editButton instanceof HTMLElement) {
                const penCode = editButton.getAttribute('data-edit-pen') ?? '';
                if (penCode !== '') {
                    const penName = editButton.getAttribute('data-edit-pen-name') ?? '';
                    const penCapacity = editButton.getAttribute('data-edit-pen-capacity') ?? '0';
                    const penStatus = editButton.getAttribute('data-edit-pen-status') ?? 'available';
                    const penNotes = editButton.getAttribute('data-edit-pen-notes') ?? '';
                    openEditModal(penCode, penName, penCapacity, penStatus, penNotes);
                }
                return;
            }

            const deleteButton = target.closest('[data-delete-pen]');
            if (! (deleteButton instanceof HTMLElement)) {
                return;
            }

            const penCode = deleteButton.getAttribute('data-delete-pen') ?? '';
            if (penCode === '') {
                return;
            }

            const penName = deleteButton.getAttribute('data-delete-pen-name') ?? '';
            const penCapacity = deleteButton.getAttribute('data-delete-pen-capacity') ?? '0';
            openDeleteModal(penCode, penName, penCapacity);
        });

        if (deleteModal instanceof HTMLElement) {
            deleteModal.addEventListener('click', function (event) {
                const target = event.target;
                if (! (target instanceof HTMLElement)) {
                    return;
                }

                if (target.closest('[data-delete-modal-close]')) {
                    closeDeleteModal();
                }
            });
        }

        if (editModal instanceof HTMLElement) {
            editModal.addEventListener('click', function (event) {
                const target = event.target;
                if (! (target instanceof HTMLElement)) {
                    return;
                }

                if (target.closest('[data-update-modal-close]')) {
                    closeEditModal();
                }
            });
        }

        if (deleteModalConfirmButton instanceof HTMLButtonElement) {
            deleteModalConfirmButton.addEventListener('click', function () {
                if (pendingDeletePenCode === '') {
                    return;
                }

                deleteModalConfirmButton.disabled = true;
                deleteModalConfirmButton.classList.add('opacity-70', 'cursor-not-allowed');

                deletePenRecord(pendingDeletePenCode)
                    .then(function (deleted) {
                        if (deleted) {
                            closeDeleteModal();
                        }
                    })
                    .finally(function () {
                        deleteModalConfirmButton.disabled = false;
                        deleteModalConfirmButton.classList.remove('opacity-70', 'cursor-not-allowed');
                    });
            });
        }

        if (editForm instanceof HTMLFormElement && editModalConfirmButton instanceof HTMLButtonElement) {
            editForm.addEventListener('submit', function (event) {
                event.preventDefault();

                if (pendingEditPenCode === '') {
                    return;
                }

                const payload = {
                    pen_name: String(editPenNameInput instanceof HTMLInputElement ? editPenNameInput.value : '').trim(),
                    capacity: Number(editPenCapacityInput instanceof HTMLInputElement ? editPenCapacityInput.value : 0),
                    status: String(editPenStatusInput instanceof HTMLSelectElement ? editPenStatusInput.value : 'available').toLowerCase(),
                    notes: String(editPenNotesInput instanceof HTMLTextAreaElement ? editPenNotesInput.value : '').trim(),
                };

                editModalConfirmButton.disabled = true;
                editModalConfirmButton.classList.add('opacity-70', 'cursor-not-allowed');

                updatePenRecord(pendingEditPenCode, payload)
                    .then(function (updated) {
                        if (updated) {
                            closeEditModal();
                        }
                    })
                    .finally(function () {
                        editModalConfirmButton.disabled = false;
                        editModalConfirmButton.classList.remove('opacity-70', 'cursor-not-allowed');
                    });
            });
        }
    });
</script>
