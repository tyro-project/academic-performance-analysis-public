/**
 * Sidebar — role-aware nav items
 */
import { Auth } from '../../js/auth.js';

const NAV_ITEMS = {
    admin: [
        { label: 'Main', items: [
            { text: 'Dashboard',  icon: 'grid',    href: '/frontend/modules/dashboard/admin/index.html' },
        ]},
        { label: 'Manage', items: [
            { text: 'Students',   icon: 'users',   href: '/frontend/modules/students/list/index.html' },
            { text: 'Faculty',    icon: 'user',    href: '/frontend/modules/faculty/list/index.html' },
            { text: 'Classes',    icon: 'book',    href: '/frontend/modules/classes/list/index.html' },
            { text: 'Subjects',   icon: 'layers',  href: '/frontend/modules/subjects/list/index.html' },
        ]},
        { label: 'Results', items: [
            { text: 'Add Result', icon: 'plus',    href: '/frontend/modules/results/add/index.html' },
            { text: 'Manage',     icon: 'edit',    href: '/frontend/modules/results/list/index.html' },
        ]},
        { label: 'Reports', items: [
            { text: 'Classwise',  icon: 'bar-chart', href: '/frontend/modules/reports/classwise/index.html' },
            { text: 'Studentwise',icon: 'pie-chart', href: '/frontend/modules/reports/studentwise/index.html' },
            { text: 'Facultywise',icon: 'trending-up', href: '/frontend/modules/reports/facultywise/index.html' },
            { text: 'Subjectwise',icon: 'activity', href: '/frontend/modules/reports/subjectwise/index.html' },
        ]},
    ],
    department: [
        { label: 'Main', items: [
            { text: 'Dashboard', icon: 'grid', href: '/frontend/modules/dashboard/dept/index.html' },
        ]},
        { label: 'Reports', items: [
            { text: 'Classwise',   icon: 'bar-chart',   href: '/frontend/modules/reports/classwise/index.html' },
            { text: 'Facultywise', icon: 'trending-up', href: '/frontend/modules/reports/facultywise/index.html' },
            { text: 'Subjectwise', icon: 'activity',    href: '/frontend/modules/reports/subjectwise/index.html' },
        ]},
    ],
    faculty: [
        { label: 'Main', items: [
            { text: 'Dashboard',  icon: 'grid',  href: '/frontend/modules/dashboard/faculty/index.html' },
            { text: 'Add Result', icon: 'plus',  href: '/frontend/modules/results/add/index.html' },
            { text: 'Manage',     icon: 'edit',  href: '/frontend/modules/results/list/index.html' },
        ]},
    ],
    student: [
        { label: 'Main', items: [
            { text: 'Dashboard',  icon: 'grid',     href: '/frontend/modules/dashboard/student/index.html' },
            { text: 'My Results', icon: 'award',    href: '/frontend/modules/results/find/index.html' },
        ]},
    ],
};

const ICONS = {
    grid:       `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>`,
    users:      `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
    user:       `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
    book:       `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>`,
    layers:     `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>`,
    plus:       `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>`,
    edit:       `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>`,
    'bar-chart':`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/><line x1="2" y1="20" x2="22" y2="20"/></svg>`,
    'pie-chart':`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>`,
    'trending-up':`<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>`,
    activity:   `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>`,
    award:      `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>`,
};

function initSidebar(containerId = 'sidebar') {
    const user    = Auth.user();
    const items   = NAV_ITEMS[user?.role] ?? [];
    const current = window.location.pathname;
    const sidebar = document.getElementById(containerId);
    if (!sidebar) return;

    sidebar.innerHTML = items.map(section => `
        <div class="sidebar-section">
            <span class="sidebar-label">${section.label}</span>
            ${section.items.map(item => `
                <a href="${item.href}" class="sidebar-item ${current.includes(item.href) ? 'active' : ''}">
                    ${ICONS[item.icon] ?? ''}
                    <span>${item.text}</span>
                </a>
            `).join('')}
        </div>
    `).join('');
}

export { initSidebar };
