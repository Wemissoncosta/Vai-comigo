const AUTH_KEY = 'vaicomigo_auth';

document.addEventListener('DOMContentLoaded', function() {
    // Remover a interceptação do formulário de login
    // O formulário agora será enviado normalmente para o servidor PHP
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

let map;
function initMap() {
    //Colinas do Tocantins
    const defaultCenter = { lat: -8.0574, lng: -48.4757 };
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultCenter,
        zoom: 12,
        styles: mapStyle 
    });
}
const mapStyle = [
    {
        "featureType": "all",
        "elementType": "geometry.fill",
        "stylers": [{"weight": "2.00"}]
    },
];