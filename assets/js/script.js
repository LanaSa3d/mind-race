document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const navMenu = document.querySelector('[data-nav-menu]');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }

    document.querySelectorAll('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            const message = button.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('form[data-validate]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const invalidField = Array.from(form.querySelectorAll('[required]')).find((field) => {
                return String(field.value).trim() === '';
            });

            if (invalidField) {
                event.preventDefault();
                invalidField.focus();
                form.classList.add('form-was-validated');
            }
        });
    });

    const timer = document.querySelector('[data-countdown]');
    const responseInput = document.querySelector('[name="response_time"]');
    const quizForm = document.querySelector('[data-play-form]');

    if (timer && responseInput && quizForm) {
        const limit = Number(timer.getAttribute('data-countdown'));
        let remaining = Number.isFinite(limit) && limit > 0 ? limit : 30;
        let elapsed = 0;

        const renderTimer = () => {
            timer.textContent = String(remaining);
            timer.classList.toggle('timer-low', remaining <= 5);
        };

        renderTimer();

        const interval = window.setInterval(() => {
            remaining -= 1;
            elapsed += 1;
            responseInput.value = String(elapsed);
            renderTimer();

            if (remaining <= 0) {
                window.clearInterval(interval);
                quizForm.submit();
            }
        }, 1000);

        quizForm.addEventListener('submit', () => {
            responseInput.value = String(elapsed);
        });
    }
});
