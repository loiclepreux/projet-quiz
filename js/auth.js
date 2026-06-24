const overlay  = document.getElementById('auth-overlay');
const loginBtn = document.getElementById('loginBtn');
const closeBtn = document.getElementById('close-auth');

if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        overlay.style.display = 'flex';
    });
}

if (closeBtn) {
    closeBtn.addEventListener('click', () => {
        overlay.style.display = 'none';
    });
}

if (overlay) {
    window.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.style.display = 'none';
    });
}

const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const base  = overlay.dataset.base;
        const error = document.getElementById('login-error');

        const res  = await fetch(`${base}php/auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action:      'login',
                identifiant: document.getElementById('login-identifiant').value,
                password:    document.getElementById('login-password').value,
            })
        });
        const data = await res.json();

        if (data.success) {
            location.reload();
        } else {
            error.textContent = data.message;
        }
    });
}

const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const base  = overlay.dataset.base;
        const error = document.getElementById('register-error');

        const res  = await fetch(`${base}php/auth.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action:   'register',
                pseudo:   document.getElementById('register-pseudo').value,
                email:    document.getElementById('register-email').value,
                password: document.getElementById('register-password').value,
            })
        });
        const data = await res.json();

        if (data.success) {
            location.reload();
        } else {
            error.textContent = data.message;
        }
    });
}
