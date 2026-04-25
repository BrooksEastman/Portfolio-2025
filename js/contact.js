(function () {
    var form = document.getElementById('contactForm');
    if (!form) return;

    var status = document.getElementById('contactStatus');
    var btn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        btn.disabled = true;
        btn.textContent = 'Sending…';
        status.textContent = '';
        status.className = 'contact-form-status';

        fetch('/contact.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                status.textContent = "Message sent! I’ll be in touch soon.";
                status.classList.add('success');
                form.reset();
            } else {
                status.textContent = data.error || 'Something went wrong. Please try again.';
                status.classList.add('error');
            }
        })
        .catch(function () {
            status.textContent = 'Something went wrong. Please try emailing me directly.';
            status.classList.add('error');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = 'Send Message';
        });
    });
})();
