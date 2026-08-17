/**
 * api.js — Centralized API Client
 * Usage: import { api } from '/core/js/api.js';
 */

const BASE_API = '/backend/services';

const api = {

    /**
     * Core fetch wrapper
     */
    async request(endpoint, options = {}) {
        const url = `${BASE_API}/${endpoint}`;
        const config = {
            credentials: 'include',
            headers: { 'Content-Type': 'application/json', ...options.headers },
            ...options,
        };

        // Don't set Content-Type for FormData (file uploads)
        if (options.body instanceof FormData) {
            delete config.headers['Content-Type'];
        }

        try {
            const res  = await fetch(url, config);
            const data = await res.json();

            if (res.status === 401) {
                window.location.href = '/frontend/modules/auth/role-selection/index.html';
                return;
            }

            if (res.status === 403) {
                Toast.error('You do not have permission to perform this action');
                return null;
            }

            if (!data.success) {
                Toast.error(data.message || 'Something went wrong');
                return null;
            }

            return data;

        } catch (err) {
            Toast.error('Network error — please check your connection');
            console.error('[API Error]', err);
            return null;
        }
    },

    get(endpoint, params = {}) {
        const query = new URLSearchParams(params).toString();
        const url   = query ? `${endpoint}?${query}` : endpoint;
        return this.request(url, { method: 'GET' });
    },

    post(endpoint, body = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: body instanceof FormData ? body : JSON.stringify(body),
        });
    },

    // Convenience methods per service
    auth: {
        login:          (role, username, password) => api.post('auth/login.php', { role, username, password }),
        logout:         ()                         => api.post('auth/logout.php'),
        me:             ()                         => api.get('auth/me.php'),
        changePassword: (body)                     => api.post('auth/change-password.php', body),
    },

    students: {
        list:   (params) => api.get('students/list.php', params),
        get:    (id)     => api.get('students/get.php', { id }),
        create: (body)   => api.post('students/create.php', body),
        update: (body)   => api.post('students/update.php', body),
        delete: (id)     => api.post('students/delete.php', { StudentId: id }),
        import: (form)   => api.post('students/import.php', form),
    },

    faculty: {
        list:         (params) => api.get('faculty/list.php', params),
        get:          (id)     => api.get('faculty/get.php', { id }),
        create:       (body)   => api.post('faculty/create.php', body),
        update:       (body)   => api.post('faculty/update.php', body),
        delete:       (id)     => api.post('faculty/delete.php', { id }),
        combinations: ()       => api.get('faculty/combinations.php'),
        addCombination:    (body) => api.post('faculty/combinations.php', { ...body, action: 'create' }),
        deleteCombination: (id)   => api.post('faculty/combinations.php', { id, action: 'delete' }),
    },

    classes: {
        list:   (params) => api.get('classes/list.php', params),
        get:    (id)     => api.get('classes/get.php', { id }),
        create: (body)   => api.post('classes/create.php', body),
        update: (body)   => api.post('classes/update.php', body),
        delete: (id)     => api.post('classes/delete.php', { id }),
    },

    subjects: {
        list:         (params) => api.get('subjects/list.php', params),
        get:          (id)     => api.get('subjects/get.php', { id }),
        create:       (body)   => api.post('subjects/create.php', body),
        update:       (body)   => api.post('subjects/update.php', body),
        delete:       (id)     => api.post('subjects/delete.php', { id }),
        combinations: ()       => api.get('subjects/combinations.php'),
        addCombination:    (body) => api.post('subjects/combinations.php', { ...body, action: 'create' }),
        deleteCombination: (id)   => api.post('subjects/combinations.php', { id, action: 'delete' }),
    },

    results: {
        list:     (params) => api.get('results/list.php', params),
        add:      (body)   => api.post('results/add.php', body),
        edit:     (body)   => api.post('results/edit.php', body),
        delete:   (id)     => api.post('results/delete.php', { id }),
        find:     (params) => api.get('results/find.php', params),
        download: (studentId) => window.open(`${BASE_API}/results/download.php?student_id=${studentId}`, '_blank'),
    },

    reports: {
        classwise:   (params) => api.get('reports/classwise.php', params),
        studentwise: (params) => api.get('reports/studentwise.php', params),
        facultywise: ()       => api.get('reports/facultywise.php'),
        subjectwise: ()       => api.get('reports/subjectwise.php'),
    },
};

export { api };
