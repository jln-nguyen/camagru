const changePasswordButton = document.querySelector('#change-password-btn');
const passwordField = document.querySelector('#password-field');
const changePasswordInput = document.querySelector('#change-password');

if (changePasswordButton && passwordField) {
    changePasswordButton.addEventListener('click', function () {
        passwordField.classList.toggle('visible');

        if (passwordField.classList.contains('visible')) {
            changePasswordInput.value = '1';
        } else {
            changePasswordInput.value = '0';
        }
    });
}
