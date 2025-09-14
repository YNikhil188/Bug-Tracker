document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle with animation
    const toggle = document.getElementById('themeToggle');
    const body = document.body;
    const icon = toggle.querySelector('i');
    const navLinks = document.querySelectorAll('.navbar .nav-link');
    toggle.addEventListener('click', function() {
        body.classList.toggle('dark-theme');
        body.classList.toggle('light-theme');
        icon.classList.toggle('fa-moon');
        icon.classList.toggle('fa-sun');
        icon.style.transition = 'transform 0.3s ease';
        icon.style.transform = icon.classList.contains('fa-sun') ? 'rotate(360deg)' : 'rotate(0deg)';
        navLinks.forEach(link => {
            link.style.color = getComputedStyle(body).getPropertyValue(body.classList.contains('dark-theme') ? '--navbar-text' : '--navbar-text').trim();
        });
        localStorage.setItem('theme', body.classList.contains('dark-theme') ? 'dark' : 'light');
    });
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-theme');
        body.classList.remove('light-theme');
        icon.classList.replace('fa-moon', 'fa-sun');
        navLinks.forEach(link => link.style.color = getComputedStyle(body).getPropertyValue('--navbar-text').trim());
    }

    // Chart initialization with responsiveness
    window.initCharts = function(chartConfigs) {
        chartConfigs.forEach(config => {
            const ctx = document.getElementById(config.canvasId).getContext('2d');
            new Chart(ctx, {
                ...config.options,
                options: {
                    ...config.options.options,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: getComputedStyle(body).getPropertyValue('--text-color').trim() } },
                        tooltip: { backgroundColor: getComputedStyle(body).getPropertyValue('--card-bg').trim() }
                    }
                }
            });
        });
    };

    // Modal and form enhancements
    const modals = document.querySelectorAll('[data-bs-toggle="modal"]');
    modals.forEach(modal => modal.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById(this.dataset.bsTarget);
        const bsModal = new bootstrap.Modal(target, { backdrop: 'static' });
        bsModal.show();
    }));

    // Add subtle hover effects to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('mouseover', () => btn.style.transform = 'scale(1.05)');
        btn.addEventListener('mouseout', () => btn.style.transform = 'scale(1)');
    });
});