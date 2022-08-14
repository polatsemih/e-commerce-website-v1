const eyeBtns = document.querySelectorAll('.btn-action-password');
eyeBtns.forEach((eyeBtn) => {
    eyeBtn.addEventListener('click', () => {
        let inputPassword = eyeBtn.parentElement.firstElementChild;
        if (inputPassword.type === 'password') {
            inputPassword.type = 'text';
            eyeBtn.classList.remove('fa-eye-slash');
            eyeBtn.classList.add('fa-eye');
        } else {
            inputPassword.type = 'password';
            eyeBtn.classList.remove('fa-eye');
            eyeBtn.classList.add('fa-eye-slash');
        }
    });
});