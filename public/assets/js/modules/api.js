/**
 * Summit University Alumni Platform — Unified API Client
 */

const SummitAPI = (function() {
    const BASE_URL = '/api';

    function getAuthToken() {
        return localStorage.getItem('summit_auth_token') || null;
    }

    function showToast(message, type = 'info') {
        let container = document.getElementById('summit-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'summit-toast-container';
            container.className = 'summit-toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `summit-toast summit-toast-${type}`;

        let icon = 'fa-info-circle';
        if (type === 'success') icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';

        toast.innerHTML = `<i class="fa ${icon}"></i> <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    async function request(endpoint, options = {}) {
        const url = endpoint.startsWith('http') ? endpoint : `${BASE_URL}${endpoint}`;
        const headers = {
            'Accept': 'application/json',
            ...(options.headers || {})
        };

        const token = getAuthToken();
        if (token && !headers['Authorization']) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        if (!(options.body instanceof FormData) && options.body && typeof options.body === 'object') {
            headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }

        try {
            const response = await fetch(url, { ...options, headers });
            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                const errorMessage = data.message || `Request failed with status ${response.status}`;
                if (response.status === 401 && token) {
                    // Session expired
                    SummitAuth.logout(false);
                }
                return { success: false, status: response.status, message: errorMessage, data };
            }

            return { success: true, status: response.status, ...data };
        } catch (err) {
            console.error('API Error:', err);
            return {
                success: false,
                status: 0,
                message: 'Unable to connect to the server. Please check your internet connection.',
            };
        }
    }

    return {
        get: (endpoint, headers) => request(endpoint, { method: 'GET', headers }),
        post: (endpoint, body, headers) => request(endpoint, { method: 'POST', body, headers }),
        put: (endpoint, body, headers) => request(endpoint, { method: 'PUT', body, headers }),
        delete: (endpoint, headers) => request(endpoint, { method: 'DELETE', headers }),
        toast: showToast,
    };
})();

window.SummitAPI = SummitAPI;
