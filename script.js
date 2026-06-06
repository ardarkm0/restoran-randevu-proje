function showForm(formId, clickedTab) {
    // Hide all forms
    document.querySelectorAll('.form-box').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

    // Show target form
    document.getElementById(formId).classList.add('active');

    // Activate tab button
    if (clickedTab) {
        clickedTab.classList.add('active');
        // Move indicator
        const indicator = document.getElementById('tab-indicator');
        if (clickedTab.id === 'tab-register') {
            indicator.classList.add('right');
        } else {
            indicator.classList.remove('right');
        }
    }
}

// ── GLOBAL THEME TOGGLE ──
document.addEventListener('DOMContentLoaded', () => {
    // Load theme from localStorage
    if (localStorage.getItem('lumina_theme') === 'light') {
        document.body.classList.add('light-mode');
    }

    const globalThemeBtn = document.getElementById('globalThemeToggle');
    if (globalThemeBtn) {
        globalThemeBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            if (document.body.classList.contains('light-mode')) {
                localStorage.setItem('lumina_theme', 'light');
            } else {
                localStorage.setItem('lumina_theme', 'dark');
            }
        });
    }
});

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    btn.style.color = isPassword ? 'var(--gold)' : '';
}

// Generate floating particles
(function createParticles() {
    const container = document.getElementById('particles');
    if (!container) return;
    for (let i = 0; i < 35; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.cssText = `
            left: ${Math.random() * 100}%;
            bottom: ${Math.random() * 30}%;
            --dur: ${4 + Math.random() * 6}s;
            --delay: ${Math.random() * 8}s;
            width: ${1 + Math.random() * 2}px;
            height: ${1 + Math.random() * 2}px;
            opacity: ${0.1 + Math.random() * 0.4};
        `;
        container.appendChild(p);
    }
})();
