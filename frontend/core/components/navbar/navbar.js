import { Auth } from '../../js/auth.js';

async function initNavbar() {
    const user = Auth.user();
    if (!user) return;
    document.getElementById('userName').textContent  = user.name;
    document.getElementById('userRole').textContent  = user.role;
    document.getElementById('userAvatar').textContent = user.name?.charAt(0).toUpperCase() ?? '?';
}

document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.querySelector('.app-sidebar')?.classList.toggle('sidebar--hidden');
});

export { initNavbar };
