const loginBtn = document.querySelector('.btn-nav[href="login.php"]');
    const authOverlay = document.getElementById('auth-overlay');
    const closeBtn = document.getElementById('close-auth');

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    authOverlay.style.display = 'flex';
});

    closeBtn.addEventListener('click', () => {
    authOverlay.style.display = 'none';
});

    window.addEventListener('click', (e) => {
        if (e.target === authOverlay) {
        authOverlay.style.display = 'none';
    }
});

