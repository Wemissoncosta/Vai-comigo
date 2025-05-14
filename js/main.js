const AUTH_KEY = 'vaicomigo_auth';

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('#formulario-login');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const matricula = e.target.querySelector('input[type="text"]').value;
            const password = e.target.querySelector('input[type="password"]').value;
            

            if (matricula == '202520242023' && password == '123456') {
                localStorage.setItem(AUTH_KEY, JSON.stringify({ matricula, name: 'Aluno Especial' }));
                location.href = 'home.html';
            } else if (matricula === '9' && password === '123456') {
                localStorage.setItem(AUTH_KEY, JSON.stringify({ matricula, name: 'Gestor Especial' }));
                location.href = 'gestor.html';
            } else {
                showAlert('Matrícula ou senha inválidos', 'danger');
            }
        });
    }
});


function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);

    setTimeout(() => alertDiv.remove(), 3000);
}


function togglePassword(button) {
    const input = button.previousElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}


function showRecoveryModal() {
    const recoveryModal = document.getElementById('recoveryModal');
    const modal = new bootstrap.Modal(recoveryModal);
    modal.show();
}


function submitRecovery() {
    const form = document.getElementById('recoveryForm');
    if (form.checkValidity()) {
        showAlert('Instruções de recuperação foram enviadas para seu e-mail.', 'success');
        bootstrap.Modal.getInstance(document.getElementById('recoveryModal')).hide();
        form.reset();
    } else {
        form.reportValidity();
    }
}
