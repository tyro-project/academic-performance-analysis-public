import { Modal } from '../../js/utils.js';

/**
 * Show confirmation dialog
 * @param {string} message
 * @param {function} onConfirm
 * @param {string} title
 */
function showConfirm(message, onConfirm, title = 'Are you sure?') {
    document.getElementById('confirmTitle').textContent   = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmBtn').onclick = () => {
        Modal.close('confirmModal');
        onConfirm();
    };
    Modal.open('confirmModal');
}

export { showConfirm };
