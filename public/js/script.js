const userMenu = document.querySelector('.user-menu');
const userButton = document.querySelector('.user-button');

if (userMenu && userButton) {
    userButton.addEventListener('click', function (event) {
        event.stopPropagation();

        const isOpen = userMenu.classList.toggle('active');

        userButton.setAttribute('aria-expanded', isOpen);
    });

    document.addEventListener('click', function () {
        userMenu.classList.remove('active');
        userButton.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            userMenu.classList.remove('active');
            userButton.setAttribute('aria-expanded', 'false');
        }
    });
}

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