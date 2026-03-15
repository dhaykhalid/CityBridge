// script.js — CityBridge

document.addEventListener('DOMContentLoaded', function () {

    function validateEmail(email) {
        if (!email) return 'Email is required.';
        if (!email.includes('@') || !email.includes('.')) return 'Email must contain @ and .';
        return null;
    }

    function validatePassword(password) {
        if (!password) return 'Password is required.';
        if (password.length < 8) return 'Password must be at least 8 characters.';
        if (!/[A-Z]/.test(password)) return 'Password must contain an uppercase letter.';
        if (!/[a-z]/.test(password)) return 'Password must contain a lowercase letter.';
        if (!/[0-9]/.test(password)) return 'Password must contain a number.';
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) return 'Password must contain a special character.';
        return null;
    }

    function showError(input, message) {
        let err = input.parentElement.querySelector('.error-message');
        if (!err) {
            err = document.createElement('div');
            err.className = 'error-message';
            err.style.color = '#e05c5c';
            err.style.fontSize = '0.75rem';
            err.style.marginTop = '5px';
            input.parentElement.appendChild(err);
        }
        err.textContent = message;
        err.style.display = 'block';
        input.style.borderColor = '#e05c5c';
    }

    function hideError(input) {
        const err = input.parentElement.querySelector('.error-message');
        if (err) err.style.display = 'none';
        input.style.borderColor = '';
    }


    // LOGIN

    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        const emailInput    = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        emailInput.addEventListener('blur',  function () { const e = validateEmail(this.value);    e ? showError(emailInput, e)    : hideError(emailInput); });
        passwordInput.addEventListener('blur', function () { const e = validatePassword(this.value); e ? showError(passwordInput, e) : hideError(passwordInput); });
        emailInput.addEventListener('input',    function () { hideError(emailInput); });
        passwordInput.addEventListener('input', function () { hideError(passwordInput); });

        loginForm.querySelectorAll('button[type="submit"]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const emailErr = validateEmail(emailInput.value);
                const passErr  = validatePassword(passwordInput.value);
                emailErr ? showError(emailInput, emailErr)    : hideError(emailInput);
                passErr  ? showError(passwordInput, passErr)  : hideError(passwordInput);
                if (!emailErr && !passErr) {
                    window.location.href = btn.getAttribute('formaction') || 'user.html';
                }
            });
        });
    }


    // SIGNUP

    const signupForm = document.getElementById('signupForm');

    if (signupForm) {
        const fields = [
            { id: 'company',         rule: function (v) { return v.trim() ? null : 'Company name is required.'; } },
            { id: 'industry',        rule: function (v) { return v ? null : 'Please select an industry.'; } },
            { id: 'firstName',       rule: function (v) { return v.trim() ? null : 'First name is required.'; } },
            { id: 'lastName',        rule: function (v) { return v.trim() ? null : 'Last name is required.'; } },
            { id: 'jobTitle',        rule: function (v) { return v.trim() ? null : 'Job title is required.'; } },
            { id: 'email',           rule: function (v) { return validateEmail(v); } },
            { id: 'phone',           rule: function (v) { return v.trim() ? null : 'Phone number is required.'; } },
            { id: 'username',        rule: function (v) { return v.trim().length >= 3 ? null : 'Username must be at least 3 characters.'; } },
            { id: 'password',        rule: function (v) { return validatePassword(v); } },
            { id: 'confirmPassword', rule: function (v) { return v === document.getElementById('password').value ? null : 'Passwords do not match.'; } },
        ];

        fields.forEach(function (f) {
            const el = document.getElementById(f.id);
            if (!el) return;
            el.addEventListener('blur',  function () { const err = f.rule(el.value); err ? showError(el, err) : hideError(el); });
            el.addEventListener('input', function () { hideError(el); });
        });

        signupForm.addEventListener('submit', function (e) {
            e.preventDefault();
            let valid = true;
            fields.forEach(function (f) {
                const el = document.getElementById(f.id);
                if (!el) return;
                const err = f.rule(el.value);
                err ? (showError(el, err), valid = false) : hideError(el);
            });
            if (valid) window.location.href = 'user.html';
        });
    }

}); 