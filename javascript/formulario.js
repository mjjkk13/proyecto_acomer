// Manejar envío de formulario
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var emailInput = document.getElementById('usuario');
    var passwordInput = document.getElementById('inputPassword');
    var isValid = true;

    // Validar entrada de usuario
    if (!emailInput.checkValidity()) {
        emailInput.classList.add('is-invalid');
        emailInput.nextElementSibling.style.display = 'block';
        isValid = false;
    } else {
        emailInput.classList.remove('is-invalid');
        emailInput.nextElementSibling.style.display = 'none';
    }

    // Validar entrada de contraseña
    if (!passwordInput.checkValidity()) {
        passwordInput.classList.add('is-invalid');
        passwordInput.nextElementSibling.style.display = 'block';
        isValid = false;
    } else {
        passwordInput.classList.remove('is-invalid');
        passwordInput.nextElementSibling.style.display = 'none';
    }

    // Prevenir el envío del formulario si hay campos inválidos
    if (!isValid) {
        event.preventDefault();
    }
});

// Manejar mostrar/ocultar contraseña
const inputPassword = document.getElementById("inputPassword");
const icon = document.getElementById("togglePassword");

icon.addEventListener("click", () => {
    if (inputPassword.type === "password") {
        inputPassword.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        inputPassword.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
