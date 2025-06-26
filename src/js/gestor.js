// Gestor JavaScript - Funcionalidades CRUD com Popups

// Variáveis globais
let modalUsuario, modalCarona;

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));
    modalCarona = new bootstrap.Modal(document.getElementById('modalCarona'));
    
    // Event listeners para formulários
    document.getElementById('formUsuario').addEventListener('submit', salvarUsuario);
    document.getElementById('formCarona').addEventListener('submit', salvarCarona);
    
    // Inicializar gráficos
    inicializarGraficos();
});

// Funções para Usuários
function abrirModalUsuario(id = null) {
    const modal = document.getElementById('modalUsuario');
    const titulo = document.getElementById('modalUsuarioTitulo');
    const form = document.getElementById('formUsuario');
    
    // Limpar formulário
    form.reset();
    document.getElementById('usuario_id').value = '';
    
    if (id) {
        titulo.textContent = 'Editar Usuário';
        carregarDadosUsuario(id);
    } else {
        titulo.textContent = 'Novo Usuário';
        document.getElementById('usuario_senha').required = true;
    }
    
    modalUsuario.show();
}

function carregarDadosUsuario(id) {
    fetch('gestor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=get_usuario&id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const user = data.data;
            document.getElementById('usuario_id').value = user.id;
            document.getElementById('usuario_nome').value = user.nome;
            document.getElementById('usuario_email').value = user.email;
            document.getElementById('usuario_telefone').value = user.telefone || '';
            document.getElementById('usuario_tipo').value = user.tipo_usuario;
            document.getElementById('usuario_matricula').value = user.matricula || '';
            document.getElementById('usuario_senha').required = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao carregar dados do usuário', 'danger');
    });
}

function salvarUsuario(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const id = document.getElementById('usuario_id').value;
    const action = id ? 'atualizar_usuario' : 'criar_usuario';
    formData.append('action', action);
    
    fetch('gestor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            modalUsuario.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao salvar usuário', 'danger');
    });
}

function editarUsuario(id) {
    abrirModalUsuario(id);
}

function deletarUsuario(id) {
    if (confirm('Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita.')) {
        fetch('gestor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=deletar_usuario&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro ao deletar usuário', 'danger');
        });
    }
}

function alterarStatusUsuario(id, ativo) {
    const acao = ativo ? 'ativar' : 'desativar';
    if (confirm(`Tem certeza que deseja ${acao} este usuário?`)) {
        fetch('gestor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=alterar_status_usuario&id=${id}&ativo=${ativo}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro ao alterar status do usuário', 'danger');
        });
    }
}

function filtrarUsuarios() {
    const busca = document.getElementById('filtro-usuario').value;
    const tipo = document.getElementById('filtro-tipo-usuario').value;
    
    fetch('gestor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=buscar_usuarios&busca=${encodeURIComponent(busca)}&tipo=${tipo}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            atualizarTabelaUsuarios(data.data);
        } else {
            mostrarAlerta('Erro ao filtrar usuários', 'danger');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao filtrar usuários', 'danger');
    });
}

function atualizarTabelaUsuarios(usuarios) {
    const tbody = document.getElementById('tabela-usuarios');
    tbody.innerHTML = '';
    
    usuarios.forEach(user => {
        const tipoClass = user.tipo_usuario === 'admin' ? 'danger' : (user.tipo_usuario === 'gestor' ? 'warning' : 'info');
        const statusClass = user.ativo == 1 ? 'success' : 'secondary';
        const statusText = user.ativo == 1 ? 'Ativo' : 'Inativo';
        const statusIcon = user.ativo == 1 ? 'ban' : 'check';
        const statusAction = user.ativo == 1 ? 0 : 1;
        const statusTitle = user.ativo == 1 ? 'Desativar' : 'Ativar';
        
        const row = `
            <tr>
                <td>${user.id}</td>
                <td>${escapeHtml(user.nome)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td><span class="badge bg-${tipoClass}">${user.tipo_usuario.charAt(0).toUpperCase() + user.tipo_usuario.slice(1)}</span></td>
                <td>${user.matricula || '-'}</td>
                <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                <td>${formatarData(user.data_cadastro)}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="editarUsuario(${user.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-${user.ativo == 1 ? 'warning' : 'success'}" 
                                onclick="alterarStatusUsuario(${user.id}, ${statusAction})" 
                                title="${statusTitle}">
                            <i class="fas fa-${statusIcon}"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="deletarUsuario(${user.id})" title="Deletar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Funções para Caronas
function abrirModalCarona(id = null) {
    const modal = document.getElementById('modalCarona');
    const titulo = document.getElementById('modalCaronaTitulo');
    const form = document.getElementById('formCarona');
    
    // Limpar formulário
    form.reset();
    document.getElementById('carona_id').value = '';
    
    if (id) {
        titulo.textContent = 'Editar Carona';
        carregarDadosCarona(id);
    } else {
        titulo.textContent = 'Nova Carona';
        // Definir data mínima como hoje
        document.getElementById('carona_data').min = new Date().toISOString().split('T')[0];
    }
    
    modalCarona.show();
}

function carregarDadosCarona(id) {
    fetch('gestor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=get_carona&id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const carona = data.data;
            document.getElementById('carona_id').value = carona.id;
            document.getElementById('carona_motorista').value = carona.motorista_id;
            document.getElementById('carona_origem').value = carona.origem;
            document.getElementById('carona_destino').value = carona.destino;
            document.getElementById('carona_data').value = carona.data_viagem;
            document.getElementById('carona_hora').value = carona.hora_viagem;
            document.getElementById('carona_vagas').value = carona.vagas_disponiveis;
            document.getElementById('carona_preco').value = carona.preco || '';
            document.getElementById('carona_observacoes').value = carona.observacoes || '';
            document.getElementById('carona_status').value = carona.status;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao carregar dados da carona', 'danger');
    });
}

function salvarCarona(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const id = document.getElementById('carona_id').value;
    const action = id ? 'atualizar_carona' : 'criar_carona';
    formData.append('action', action);
    
    fetch('gestor.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            modalCarona.hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao salvar carona', 'danger');
    });
}

function editarCarona(id) {
    abrirModalCarona(id);
}

function deletarCarona(id) {
    if (confirm('Tem certeza que deseja deletar esta carona? Esta ação não pode ser desfeita.')) {
        fetch('gestor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=deletar_carona&id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarAlerta(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            mostrarAlerta('Erro ao deletar carona', 'danger');
        });
    }
}

function filtrarCaronas() {
    const origem = document.getElementById('filtro-origem').value;
    const destino = document.getElementById('filtro-destino').value;
    const data = document.getElementById('filtro-data').value;
    const status = document.getElementById('filtro-status-carona').value;
    
    fetch('gestor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=buscar_caronas&origem=${encodeURIComponent(origem)}&destino=${encodeURIComponent(destino)}&data=${data}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            atualizarTabelaCaronas(data.data);
        } else {
            mostrarAlerta('Erro ao filtrar caronas', 'danger');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarAlerta('Erro ao filtrar caronas', 'danger');
    });
}

function atualizarTabelaCaronas(caronas) {
    const tbody = document.getElementById('tabela-caronas');
    tbody.innerHTML = '';
    
    caronas.forEach(carona => {
        const statusClass = carona.status === 'ativa' ? 'success' : (carona.status === 'finalizada' ? 'primary' : 'danger');
        const dataHora = formatarDataHora(carona.data_viagem, carona.hora_viagem);
        const preco = carona.preco ? `R$ ${parseFloat(carona.preco).toFixed(2).replace('.', ',')}` : 'Gratuita';
        
        const row = `
            <tr>
                <td>${carona.id}</td>
                <td>${escapeHtml(carona.motorista_nome)}</td>
                <td>${escapeHtml(carona.origem)}</td>
                <td>${escapeHtml(carona.destino)}</td>
                <td>${dataHora}</td>
                <td>${carona.vagas_disponiveis}</td>
                <td>${preco}</td>
                <td><span class="badge bg-${statusClass}">${carona.status.charAt(0).toUpperCase() + carona.status.slice(1)}</span></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="editarCarona(${carona.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="deletarCarona(${carona.id})" title="Deletar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Funções de Gráficos
function inicializarGraficos() {
    // Gráfico de Usuários
    const ctxUsuarios = document.getElementById('graficoUsuarios').getContext('2d');
    new Chart(ctxUsuarios, {
        type: 'doughnut',
        data: {
            labels: ['Alunos', 'Gestores', 'Administradores'],
            datasets: [{
                data: [
                    document.querySelectorAll('tbody tr .badge:contains("Aluno")').length,
                    document.querySelectorAll('tbody tr .badge:contains("Gestor")').length,
                    document.querySelectorAll('tbody tr .badge:contains("Admin")').length
                ],
                backgroundColor: ['#17a2b8', '#ffc107', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Caronas
    const ctxCaronas = document.getElementById('graficoCaronas').getContext('2d');
    new Chart(ctxCaronas, {
        type: 'bar',
        data: {
            labels: ['Ativas', 'Finalizadas', 'Canceladas'],
            datasets: [{
                label: 'Quantidade',
                data: [
                    document.querySelectorAll('#tabela-caronas tr .badge.bg-success').length,
                    document.querySelectorAll('#tabela-caronas tr .badge.bg-primary').length,
                    document.querySelectorAll('#tabela-caronas tr .badge.bg-danger').length
                ],
                backgroundColor: ['#28a745', '#007bff', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Funções Utilitárias
function mostrarAlerta(mensagem, tipo) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertContainer.innerHTML = `
        ${mensagem}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatarData(data) {
    return new Date(data).toLocaleDateString('pt-BR');
}

function formatarDataHora(data, hora) {
    const dataObj = new Date(data + ' ' + hora);
    return dataObj.toLocaleDateString('pt-BR') + ' ' + dataObj.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
}

// Event listeners para filtros em tempo real
document.addEventListener('DOMContentLoaded', function() {
    // Filtro de usuários em tempo real
    document.getElementById('filtro-usuario').addEventListener('input', function() {
        if (this.value.length >= 3 || this.value.length === 0) {
            filtrarUsuarios();
        }
    });
    
    document.getElementById('filtro-tipo-usuario').addEventListener('change', filtrarUsuarios);
    
    // Filtros de caronas
    document.getElementById('filtro-origem').addEventListener('input', function() {
        if (this.value.length >= 3 || this.value.length === 0) {
            filtrarCaronas();
        }
    });
    
    document.getElementById('filtro-destino').addEventListener('input', function() {
        if (this.value.length >= 3 || this.value.length === 0) {
            filtrarCaronas();
        }
    });
    
    document.getElementById('filtro-data').addEventListener('change', filtrarCaronas);
    document.getElementById('filtro-status-carona').addEventListener('change', filtrarCaronas);
});
