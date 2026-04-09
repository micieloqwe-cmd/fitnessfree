// ============================================
//  ELEV8 FITNESS — Main JavaScript
// ============================================

// ── Navbar scroll effect ──
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    });
}

// ── Dropdown toggle ──
function toggleDropdown() {
    const dd = document.getElementById('navDropdown');
    if (dd) dd.classList.toggle('open');
}
document.addEventListener('click', (e) => {
    const dd = document.getElementById('navDropdown');
    const avatar = document.querySelector('.nav-avatar');
    if (dd && avatar && !avatar.contains(e.target)) {
        dd.classList.remove('open');
    }
});

// ── Mobile menu ──
function toggleMobileMenu() {
    const links = document.querySelector('.nav-links');
    if (links) links.classList.toggle('mobile-open');
}

// ── Modal helpers ──
function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('open');
    document.body.style.overflow = '';
}
// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
});

// ── Filter tabs ──
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        const group = this.closest('.filter-tabs');
        group.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        const targetGrid = this.closest('section, .filter-section')?.querySelector('.filter-grid, .grid-auto, .grid-3');
        if (!targetGrid) return;

        targetGrid.querySelectorAll('[data-tags]').forEach(item => {
            const tags = item.dataset.tags?.split(',') || [];
            const show = filter === 'all' || tags.includes(filter);
            item.style.display = show ? '' : 'none';
        });
    });
});

// ── Favorite toggle ──
function toggleFavorite(btn, type, id) {
    btn.classList.toggle('active');
    const active = btn.classList.contains('active');
    btn.querySelector('i').className = active ? 'fa-solid fa-heart' : 'fa-regular fa-heart';

    fetch('/fitness/api/favorites.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, id, action: active ? 'add' : 'remove' })
    }).catch(() => {
        btn.classList.toggle('active');
        btn.querySelector('i').className = active ? 'fa-regular fa-heart' : 'fa-solid fa-heart';
    });
}

// ── Alert auto-hide ──
document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.4s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 400);
    }, 4000);
});

// ── Progress bar animate on scroll ──
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const fill = entry.target.querySelector('.progress-fill');
            if (fill) {
                const target = fill.dataset.width || '0';
                fill.style.width = target + '%';
            }
        }
    });
}, { threshold: 0.3 });

document.querySelectorAll('.progress-bar').forEach(bar => observer.observe(bar));

// ── Stagger animation on load ──
document.querySelectorAll('.fade-up').forEach((el, i) => {
    el.style.animationDelay = `${i * 0.07}s`;
});
