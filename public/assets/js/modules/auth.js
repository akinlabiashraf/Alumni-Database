/**
 * Summit University Alumni Platform — Authentication & Session Manager
 */

const SummitAuth = (function() {
    const TOKEN_KEY = 'summit_auth_token';
    const USER_KEY = 'summit_user';

    function getToken() {
        return localStorage.getItem(TOKEN_KEY);
    }

    function getUser() {
        try {
            return JSON.parse(localStorage.getItem(USER_KEY));
        } catch {
            return null;
        }
    }

    function isAuthenticated() {
        return !!getToken();
    }

    function setSession(token, user) {
        localStorage.setItem(TOKEN_KEY, token);
        localStorage.setItem(USER_KEY, JSON.stringify(user));
        syncNavbar();
    }

    async function logout(callApi = true) {
        if (callApi && getToken()) {
            try {
                await SummitAPI.post('/logout');
            } catch (e) {
                console.error(e);
            }
        }
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
        syncNavbar();
        window.location.href = '/';
    }

    function syncNavbar() {
        const guestElements = document.querySelectorAll('.summit-nav-guest');
        const authElements = document.querySelectorAll('.summit-nav-auth');
        const userNameDisplays = document.querySelectorAll('.summit-user-name');

        const loggedIn = isAuthenticated();
        const user = getUser();

        guestElements.forEach(el => {
            el.style.display = loggedIn ? 'none' : '';
        });

        authElements.forEach(el => {
            el.style.display = loggedIn ? '' : 'none';
        });

        if (loggedIn && user) {
            userNameDisplays.forEach(el => {
                el.textContent = user.name || 'Alumnus';
            });

            const isAdminOrMod = user.role === 'admin' || user.role === 'moderator';
            document.querySelectorAll('.summit-nav-admin-link').forEach(el => {
                el.style.display = isAdminOrMod ? '' : 'none';
            });
        }
    }

    // Auto-run on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        syncNavbar();

        // Bind global logout buttons
        document.querySelectorAll('.summit-btn-logout').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                logout();
            });
        });
    });

    return {
        getToken,
        getUser,
        isAuthenticated,
        setSession,
        logout,
        syncNavbar,
    };
})();

window.SummitAuth = SummitAuth;
