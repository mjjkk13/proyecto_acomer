
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var emailInput = document.getElementById('inputEmail');
    var passwordInput = document.getElementById('inputPassword');
    var rememberMeCheckbox = document.getElementById('rememberMe');
    
    if (!emailInput.checkValidity()) {
      emailInput.classList.add('is-invalid');
      emailInput.nextElementSibling.style.display = 'block';
      event.preventDefault();
    } else {
      emailInput.classList.remove('is-invalid');
      emailInput.nextElementSibling.style.display = 'none';
    }
  
    if (!passwordInput.checkValidity()) {
      passwordInput.classList.add('is-invalid');
      passwordInput.nextElementSibling.style.display = 'block';
      event.preventDefault();
    } else {
      passwordInput.classList.remove('is-invalid');
      passwordInput.nextElementSibling.style.display = 'none';
    }
  });

