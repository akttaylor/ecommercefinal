document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('login-form');
  if (!form) return;

  const emailEl = document.getElementById('email');
  const passwordEl = document.getElementById('password');
  const successBox = document.getElementById('success-message');
  const errorBox = document.getElementById('error-message');
  const emailErr = document.getElementById('email-error');
  const passErr = document.getElementById('password-error');

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

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    setError(''); setSuccess('');
    if (emailErr) emailErr.textContent = '';
    if (passErr) passErr.textContent = '';

    const email = (emailEl?.value || '').trim();
    const password = passwordEl?.value || '';

    let hasErr = false;
    if (!email || !/^\S+@\S+\.\S+$/.test(email)) {
      if (emailErr) emailErr.textContent = 'Enter a valid email address';
      hasErr = true;
    }
    if (!password) {
      if (passErr) passErr.textContent = 'Enter your password';
      hasErr = true;
    }
    if (hasErr) return;

    try {
      const res = await fetch('../actions/login_customer_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();
      console.log('Login response:', data);
      if (data.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: data.message || 'Login successful',
          confirmButtonColor: '#2f5233',
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.href = data.redirect || '../admin/dashboard.php';
        });
      } else {
        setError(data.message || 'Login failed');
      }
    } catch (err) {
      console.error(err);
      setError('Network error. Please try again.');
    }
  });
});
