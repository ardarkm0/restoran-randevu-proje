// ── Header scroll
window.addEventListener('scroll', () => {
    const h = document.getElementById('mainHeader');
    if (h) h.classList.toggle('scrolled', window.scrollY > 60);
});

// ── Hero particles
(function () {
    const c = document.getElementById('heroParticles');
    if (!c) return;
    for (let i = 0; i < 40; i++) {
        const p = document.createElement('div');
        p.className = 'h-particle';
        const size = 1 + Math.random() * 2.5;
        p.style.cssText = `width:${size}px;height:${size}px;left:${Math.random()*100}%;bottom:${Math.random()*40}%;--d:${5+Math.random()*8}s;--dl:${Math.random()*6}s;`;
        c.appendChild(p);
    }
})();

// ── Booking modal
function openBooking() {
    document.getElementById('bookingModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeBooking() {
    document.getElementById('bookingModal').classList.remove('open');
    document.body.style.overflow = '';
}

// ── Auth modal
function openAuthModal() {
    document.getElementById('authModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeAuthModal() {
    document.getElementById('authModal').classList.remove('open');
    document.body.style.overflow = '';
}
function switchAuthTab(tab) {
    ['login','register'].forEach(t => {
        document.getElementById('auth-' + t).classList.toggle('active', t === tab);
        document.getElementById('auth-tab-' + t).classList.toggle('active', t === tab);
    });
}

// ── Full menu section tabs
function switchMenuSection(cat, btn) {
    document.querySelectorAll('.ms-category').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.ms-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('ms-' + cat).classList.add('active');
    btn.classList.add('active');
}

// ── Guest counter
let guests = 2;
function changeGuests(delta) {
    guests = Math.max(1, Math.min(20, guests + delta));
    document.getElementById('guestCount').textContent = guests;
    document.getElementById('guestInput').value = guests;
    document.getElementById('guestMinus').style.opacity = guests === 1 ? '0.3' : '1';
    document.getElementById('guestPlus').style.opacity = guests === 20 ? '0.3' : '1';
}

// ── Interactive Map Selection
function selectMapTable(el, value) {
    document.querySelectorAll('.fp-table').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('seatInput').value = value;
    document.getElementById('st-label').textContent = value;
}

// ── Menu category tabs
function switchMenu(category, btn) {
    document.querySelectorAll('.menu-category').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.menu-tab').forEach(b => b.classList.remove('active'));
    document.getElementById('menu-' + category).classList.add('active');
    btn.classList.add('active');
}

// ── Menu item toggle
const selectedItems = new Set();
function toggleMenuItem(el, name) {
    if (selectedItems.has(name)) {
        selectedItems.delete(name);
        el.classList.remove('selected');
    } else {
        selectedItems.add(name);
        el.classList.add('selected');
    }
    document.getElementById('menuInput').value = Array.from(selectedItems).join(', ');
}

// ── Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
});

// ── URL param toasts
(function () {
    const params = new URLSearchParams(window.location.search);
    const t = document.getElementById('successToast');
    if (t) {
        if (params.get('booking') === 'success') {
            t.querySelector('strong').textContent = 'Reservation Confirmed!';
            t.querySelector('span').textContent = 'We look forward to hosting you.';
            t.classList.add('show'); 
            setTimeout(() => t.classList.remove('show'), 4500);
            history.replaceState(null, '', window.location.pathname);
        } else if (params.get('subscribed') === '1') {
            t.querySelector('strong').textContent = 'Subscribed Successfully!';
            t.querySelector('span').textContent = 'Thank you for joining our newsletter.';
            t.classList.add('show'); 
            setTimeout(() => t.classList.remove('show'), 4500);
            history.replaceState(null, '', window.location.pathname);
        } else if (params.get('booking') === 'taken') {
            t.className = 'toast show toast-error';
            t.querySelector('strong').textContent = 'Timeslot Unavailable';
            t.querySelector('span').textContent = 'This date, time and seating is already booked.';
            setTimeout(() => t.classList.remove('show'), 4500);
            history.replaceState(null, '', window.location.pathname);
        } else if (params.get('booking') === 'error') {
            t.className = 'toast show toast-error';
            t.querySelector('strong').textContent = 'Booking Failed';
            t.querySelector('span').textContent = 'Please check your information and try again.';
            setTimeout(() => t.classList.remove('show'), 4500);
            history.replaceState(null, '', window.location.pathname);
        }
    }
})();

// ── Theme Toggle & User Dropdown
document.addEventListener('DOMContentLoaded', () => {
    const themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        // Load theme from localStorage
        if (localStorage.getItem('lumina_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
        themeBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            if (document.body.classList.contains('light-mode')) {
                localStorage.setItem('lumina_theme', 'light');
            } else {
                localStorage.setItem('lumina_theme', 'dark');
            }
        });
    }

    // Close user dropdown if clicked outside
    window.addEventListener('click', function(e) {
        const userMenu = document.getElementById('userMenu');
        if (userMenu && userMenu.classList.contains('show')) {
            if (!e.target.closest('.user-profile')) {
                userMenu.classList.remove('show');
            }
        }
    });
});
