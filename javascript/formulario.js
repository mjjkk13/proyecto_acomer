// Handle form submission
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var emailInput = document.getElementById('usuario');
    var passwordInput = document.getElementById('inputPassword');
    var isValid = true;

    // Validate email input
    if (!emailInput.checkValidity()) {
        emailInput.classList.add('is-invalid');
        emailInput.nextElementSibling.style.display = 'block';
        isValid = false;
    } else {
        emailInput.classList.remove('is-invalid');
        emailInput.nextElementSibling.style.display = 'none';
    }

    // Validate password input
    if (!passwordInput.checkValidity()) {
        passwordInput.classList.add('is-invalid');
        passwordInput.nextElementSibling.style.display = 'block';
        isValid = false;
    } else {
        passwordInput.classList.remove('is-invalid');
        passwordInput.nextElementSibling.style.display = 'none';
    }

    // Prevent form submission if any field is invalid
    if (!isValid) {
        event.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Verifica que el archivo JS se carga
    console.log('JavaScript file loaded');

    // Handle show password button
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('inputPassword');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            console.log('Toggle password icon clicked'); // Verifica que el evento de clic se está disparando

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.classList.remove('fa-eye');
                togglePassword.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            }
        });
    } else {
        console.log('Toggle password icon not found');
    }
});
