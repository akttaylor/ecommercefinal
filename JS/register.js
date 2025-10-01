document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    if (!form) return;

    const nameEl = document.getElementById('name');
    const emailEl = document.getElementById('email');
    const passwordEl = document.getElementById('password');
    const confirmPasswordEl = document.getElementById('confirm-password');
    const countryEl = document.getElementById('country');
    const cityEl = document.getElementById('city');
    const contactEl = document.getElementById('contact');

    const successBox = document.getElementById('success-message');
    const errorBox = document.getElementById('error-message');

    const nameErr = document.getElementById('name-error');
    const emailErr = document.getElementById('email-error');
    const passErr = document.getElementById('password-error');
    const confirmPassErr = document.getElementById('confirm-password-error');
    const countryErr = document.getElementById('country-error');
    const cityErr = document.getElementById('city-error');
    const contactErr = document.getElementById('contact-error');

    function setError(msg) {
        if (msg) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg,
                confirmButtonColor: '#2f5233'
            });
        }
    }

    function setSuccess(msg) {
        if (msg) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: msg,
                confirmButtonColor: '#2f5233'
            });
        }
    }

    // Real-time email availability check
    let emailCheckTimeout;
    if (emailEl) {
        emailEl.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);
            const email = this.value.trim();

            if (email && /^\S+@\S+\.\S+$/.test(email)) {
                emailCheckTimeout = setTimeout(async function() {
                    try {
                        const res = await fetch('../actions/check_email_action.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ email: email })
                        });
                        const data = await res.json();
                        const emailCheck = document.getElementById('email-check');
                        if (emailCheck) {
                            if (data.status === 'success') {
                                emailCheck.textContent = '✓';
                                emailCheck.style.color = '#28a745';
                            } else {
                                emailCheck.textContent = '✗';
                                emailCheck.style.color = '#dc3545';
                            }
                        }
                    } catch (err) {
                        console.error('Email check failed:', err);
                    }
                }, 500);
            }
        });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        setError('');
        setSuccess('');

        // Clear all field errors
        [nameErr, emailErr, passErr, confirmPassErr, countryErr, cityErr, contactErr].forEach(el => {
            if (el) el.textContent = '';
        });

        const name = (nameEl?.value || '').trim();
        const email = (emailEl?.value || '').trim();
        const password = passwordEl?.value || '';
        const confirmPassword = confirmPasswordEl?.value || '';
        const country = (countryEl?.value || '').trim();
        const city = (cityEl?.value || '').trim();
        const contact = (contactEl?.value || '').trim();

        let hasErr = false;

        // Validation
        if (!name || name.length < 2) {
            if (nameErr) nameErr.textContent = 'Name must be at least 2 characters';
            hasErr = true;
        }

        if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
            if (emailErr) emailErr.textContent = 'Enter a valid email address';
            hasErr = true;
        }

        if (!password || password.length < 6) {
            if (passErr) passErr.textContent = 'Password must be at least 6 characters';
            hasErr = true;
        }

        if (password !== confirmPassword) {
            if (confirmPassErr) confirmPassErr.textContent = 'Passwords do not match';
            hasErr = true;
        }

        if (!country) {
            if (countryErr) countryErr.textContent = 'Country is required';
            hasErr = true;
        }

        if (!city) {
            if (cityErr) cityErr.textContent = 'City is required';
            hasErr = true;
        }

        if (!contact || contact.length < 10) {
            if (contactErr) contactErr.textContent = 'Enter a valid contact number (min 10 digits)';
            hasErr = true;
        }

        if (hasErr) return;

        try {
            const res = await fetch('../actions/register_customer_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    name,
                    email,
                    password,
                    country,
                    city,
                    contact
                })
            });

            const data = await res.json();
            console.log('Registration response:', data);

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Registration successful! Redirecting to login...',
                    confirmButtonColor: '#2f5233',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'login.php';
                });
                form.reset();
            } else {
                setError(data.message || 'Registration failed');
            }
        } catch (err) {
            console.error(err);
            setError('Network error. Please try again.');
        }
    });
});
