/**
 * utils.js — Shared utility functions
 */

/**
 * Toast notification system
 */
const Toast = {
    _container: null,

    _getContainer() {
        if (!this._container) {
            this._container = document.createElement('div');
            this._container.id = 'toast-container';
            this._container.style.cssText = `
                position: fixed; top: 20px; right: 20px;
                z-index: 9999; display: flex;
                flex-direction: column; gap: 8px;
            `;
            document.body.appendChild(this._container);
        }
        return this._container;
    },

    _show(message, type = 'info', duration = 3500) {
        const colors = {
            success: { bg: '#f0fdf4', border: '#16a34a', text: '#15803d' },
            error:   { bg: '#fef2f2', border: '#dc2626', text: '#b91c1c' },
            warning: { bg: '#fffbeb', border: '#d97706', text: '#92400e' },
            info:    { bg: '#eff6ff', border: '#2563eb', text: '#1d4ed8' },
        };
        const icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
        const c = colors[type];

        const toast = document.createElement('div');
        toast.style.cssText = `
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px;
            background: ${c.bg}; border: 1px solid ${c.border};
            color: ${c.text}; font-size: 13px; font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            animation: slideIn .2s ease; min-width: 260px; max-width: 360px;
        `;
        toast.innerHTML = `<span style="font-size:15px">${icons[type]}</span><span>${message}</span>`;
        this._getContainer().appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity .3s';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    },

    success: (msg) => Toast._show(msg, 'success'),
    error:   (msg) => Toast._show(msg, 'error'),
    warning: (msg) => Toast._show(msg, 'warning'),
    info:    (msg) => Toast._show(msg, 'info'),
};

/**
 * Modal helper
 */
const Modal = {
    open(id)  { document.getElementById(id)?.classList.add('modal--open'); },
    close(id) { document.getElementById(id)?.classList.remove('modal--open'); },
    closeAll() {
        document.querySelectorAll('.modal--open').forEach(m => m.classList.remove('modal--open'));
    },
};

/**
 * Table renderer
 */
function renderTable(tbodyId, rows, columns) {
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="${columns.length}" style="text-align:center;padding:24px;color:#94a3b8">No records found</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(row => `
        <tr>
            ${columns.map(col => `<td>${col.render ? col.render(row) : (row[col.key] ?? '—')}</td>`).join('')}
        </tr>
    `).join('');
}

/**
 * Form helpers
 */
function getFormData(formId) {
    const form = document.getElementById(formId);
    if (!form) return {};
    return Object.fromEntries(new FormData(form));
}

function setFormData(formId, data) {
    const form = document.getElementById(formId);
    if (!form) return;
    Object.entries(data).forEach(([key, value]) => {
        const el = form.elements[key];
        if (el) el.value = value ?? '';
    });
}

function resetForm(formId) {
    document.getElementById(formId)?.reset();
}

/**
 * Debounce
 */
function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

/**
 * Date formatter
 */
function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('en-IN', {
        day: '2-digit', month: 'short', year: 'numeric',
    });
}

// Make Toast globally available (for api.js which imports before DOM)
window.Toast = Toast;

export { Toast, Modal, renderTable, getFormData, setFormData, resetForm, debounce, formatDate };
