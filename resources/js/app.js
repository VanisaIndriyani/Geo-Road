import './bootstrap';

const hideLoader = () => {
    const loader = document.getElementById('gr-loader');
    if (!loader) return;
    loader.classList.add('gr-hide');
    window.setTimeout(() => loader.remove(), 450);
};

const initToasts = () => {
    const toastEls = document.querySelectorAll('.toast[data-gr-toast="1"]');
    if (!toastEls.length) return;
    const BootstrapToast = window.bootstrap?.Toast;
    toastEls.forEach((el) => {
        if (BootstrapToast) {
            new BootstrapToast(el, { delay: 4200 }).show();
        } else {
            el.classList.add('show');
        }
    });
};

const animateCounters = () => {
    const els = document.querySelectorAll('[data-counter]');
    if (!els.length) return;

    const run = (el) => {
        const target = Number(el.getAttribute('data-counter') || '0');
        const duration = 900;
        const start = performance.now();
        const from = 0;

        const step = (now) => {
            const t = Math.min(1, (now - start) / duration);
            const value = Math.floor(from + (target - from) * (1 - Math.pow(1 - t, 3)));
            el.textContent = value.toLocaleString('id-ID');
            if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    run(entry.target);
                    io.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.35 }
    );

    els.forEach((el) => io.observe(el));
};

const initSweetConfirm = () => {
    document.addEventListener('click', (e) => {
        const target = e.target instanceof Element ? e.target.closest('[data-confirm-delete="1"]') : null;
        if (!target) return;

        const formId = target.getAttribute('data-form-id');
        if (!formId) return;
        const form = document.getElementById(formId);
        if (!form) return;

        e.preventDefault();

        const Swal = window.Swal;
        if (!Swal) {
            if (confirm('Hapus data ini?')) form.submit();
            return;
        }

        Swal.fire({
            title: 'Hapus data?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
};

const initPasswordToggles = () => {
    document.addEventListener('click', (e) => {
        const btn = e.target instanceof Element ? e.target.closest('[data-toggle-password="1"]') : null;
        if (!btn) return;
        const selector = btn.getAttribute('data-target');
        if (!selector) return;
        const input = document.querySelector(selector);
        if (!(input instanceof HTMLInputElement)) return;

        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        const icon = btn.querySelector('i');
        if (icon) icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
    });
};

const initSidebarToggle = () => {
    const toggle = document.getElementById('grSidebarToggle');
    if (!toggle) return;

    const key = 'gr_sidebar_collapsed';
    const mq = window.matchMedia('(min-width: 992px)');

    const apply = (collapsed) => {
        document.body.classList.toggle('gr-sidebar-collapsed', Boolean(collapsed));
        toggle.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
    };

    const load = () => {
        const collapsed = localStorage.getItem(key) === '1';
        apply(mq.matches ? collapsed : false);
    };

    toggle.addEventListener('click', () => {
        if (!mq.matches) return;
        const next = !document.body.classList.contains('gr-sidebar-collapsed');
        localStorage.setItem(key, next ? '1' : '0');
        apply(next);
    });

    if (mq.addEventListener) mq.addEventListener('change', load);
    else mq.addListener(load);

    load();
};

document.addEventListener('DOMContentLoaded', () => {
    hideLoader();
    initToasts();
    animateCounters();
    initSweetConfirm();
    initPasswordToggles();
    initSidebarToggle();
    if (window.AOS) window.AOS.init({ once: true, duration: 850, easing: 'ease-out-cubic' });
});
