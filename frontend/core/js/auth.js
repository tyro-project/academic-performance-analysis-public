/**
 * auth.js — Session & Role-Based Routing
 * Usage: import { Auth } from '/core/js/auth.js';
 */

import { api } from './api.js';

const ROLE_DASHBOARDS = {
    admin:      '/frontend/modules/dashboard/admin/index.html',
    department: '/frontend/modules/dashboard/dept/index.html',
    faculty:    '/frontend/modules/dashboard/faculty/index.html',
    student:    '/frontend/modules/dashboard/student/index.html',
};

const LOGIN_PAGE = '/frontend/modules/auth/role-selection/index.html';

const Auth = {

    _user: null,

    /**
     * Fetch current user from API — call on every protected page
     */
    async init(allowedRoles = []) {
        const res = await api.auth.me();
        if (!res) {
            window.location.href = LOGIN_PAGE;
            return null;
        }
        this._user = res.data;

        if (allowedRoles.length && !allowedRoles.includes(this._user.role)) {
            window.location.href = LOGIN_PAGE;
            return null;
        }

        return this._user;
    },

    /**
     * Get current user (after init)
     */
    user() {
        return this._user;
    },

    /**
     * Login helper — posts to API, redirects to dashboard
     */
    async login(role, username, password) {
        const res = await api.auth.login(role, username, password);
        if (res) {
            window.location.href = ROLE_DASHBOARDS[res.data.role] ?? LOGIN_PAGE;
        }
    },

    /**
     * Logout — clears session, redirects to login
     */
    async logout() {
        await api.auth.logout();
        window.location.href = LOGIN_PAGE;
    },

    /**
     * Redirect to correct dashboard based on role
     */
    redirectToDashboard() {
        const role = this._user?.role;
        window.location.href = ROLE_DASHBOARDS[role] ?? LOGIN_PAGE;
    },

    /**
     * Check role without redirect
     */
    hasRole(role) {
        return this._user?.role === role;
    },

    /**
     * Check if any of the roles match
     */
    hasAnyRole(roles = []) {
        return roles.includes(this._user?.role);
    },
};

export { Auth };
