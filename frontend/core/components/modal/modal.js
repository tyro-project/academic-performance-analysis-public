import { Modal } from '../../js/utils.js';

/**
 * Initialize modal event listeners — call once on page load
 */
function initModalListeners() {
    document.getElementById('closeModalBtn')?.addEventListener('click', () => {
        Modal.close('confirmModal');
    });

    document.getElementById('cancelBtn')?.addEventListener('click', () => {
        Modal.close('confirmModal');
    });
}

/**
 * Show confirmation dialog
 * @param {string} message
 * @param {function} onConfirm
 * @param {string} title
 */
function showConfirm(message, onConfirm, title = 'Are you sure?') {
    document.getElementById('confirmTitle').textContent   = title;
    document.getElementById('confirmMessage').textContent = message;
    
    // Remove previous listener to avoid duplicates
    const oldBtn = document.getElementById('confirmBtn');
    const newBtn = oldBtn.cloneNode(true);
    oldBtn.parentNode.replaceChild(newBtn, oldBtn);
    
    // Attach new listener
    document.getElementById('confirmBtn').addEventListener('click', () => {
        Modal.close('confirmModal');
        onConfirm();
    });
    
    Modal.open('confirmModal');
}

export { initModalListeners, showConfirm };
