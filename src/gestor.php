<?php
session_start();
require_once 'Classes/Usuario.php';
require_once 'Classes/Carona.php';

/*// Verifica se é admin
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado']['tipo_usuario'] !== 'admin') {
    header('Location: login.php');
    exit;
}*/
$page_title = 'Gestor - Painel Administrativo';
$page_css = 'gestor.css';

// Processa ações AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $usuario = new Usuario();
        $carona = new Carona();
        
        switch ($_POST['action']) {
            case 'criar_usuario':
                $id = $usuario->inserir(
                    $_POST['nome'],
                    $_POST['email'],
                    $_POST['senha'],
                    $_POST['telefone'] ?? null,
                    $_POST['tipo_usuario'],
                    $_POST['matricula'] ?? null,
                    $_FILES['foto'] ?? null
                );
                echo json_encode(['success' => true, 'message' => 'Usuário criado com sucesso!', 'id' => $id]);
                break;
                
            case 'atualizar_usuario':
                $result = $usuario->atualizar(
                    $_POST['id'],
                    $_POST['nome'],
                    $_POST['email'],
                    $_POST['telefone'] ?? null,
                    $_POST['tipo_usuario'],
                    $_POST['matricula'] ?? null,
                    !empty($_POST['senha']) ? $_POST['senha'] : null,
                    $_FILES['foto'] ?? null
                );
                echo json_encode(['success' => $result, 'message' => 'Usuário atualizado com sucesso!']);
                break;
                
            case 'deletar_usuario':
                $result = $usuario->deletar($_POST['id']);
                echo json_encode(['success' => $result, 'message' => 'Usuário deletado com sucesso!']);
                break;
                
            case 'alterar_status_usuario':
                $result = $usuario->alterarStatus($_POST['id'], $_POST['ativo'] == '1');
                echo json_encode(['success' => $result, 'message' => 'Status alterado com sucesso!']);
                break;
                
            case 'criar_carona':
                $id = $carona->inserir(
                    $_POST['motorista_id'],
                    $_POST['origem'],
                    $_POST['destino'],
                    $_POST['data_viagem'],
                    $_POST['hora_viagem'],
                    $_POST['vagas_disponiveis'],
                    $_POST['preco'] ?? null,
                    $_POST['observacoes'] ?? null
                );
                echo json_encode(['success' => true, 'message' => 'Carona criada com sucesso!', 'id' => $id]);
                break;
                
            case 'atualizar_carona':
                $result = $carona->atualizar(
                    $_POST['id'],
                    $_POST['origem'],
                    $_POST['destino'],
                    $_POST['data_viagem'],
                    $_POST['hora_viagem'],
                    $_POST['vagas_disponiveis'],
                    $_POST['preco'] ?? null,
                    $_POST['observacoes'] ?? null,
                    $_POST['status']
                );
                echo json_encode(['success' => $result, 'message' => 'Carona atualizada com sucesso!']);
                break;
                
            case 'deletar_carona':
                $result = $carona->deletar($_POST['id']);
                echo json_encode(['success' => $result, 'message' => 'Carona deletada com sucesso!']);
                break;
                
            case 'buscar_usuarios':
                $usuarios = Usuario::buscar($_POST['busca'] ?? '', $_POST['tipo'] ?? '');
                echo json_encode(['success' => true, 'data' => $usuarios]);
                break;
                
            case 'buscar_caronas':
                $caronas = Carona::buscar($_POST['origem'] ?? '', $_POST['destino'] ?? '', $_POST['data'] ?? '', $_POST['status'] ?? '');
                echo json_encode(['success' => true, 'data' => $caronas]);
                break;
                
            case 'get_usuario':
                $user = Usuario::getById($_POST['id']);
                echo json_encode(['success' => true, 'data' => $user]);
                break;
                
            case 'get_carona':
                $car = Carona::getById($_POST['id']);
                echo json_encode(['success' => true, 'data' => $car]);
                break;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Busca dados para exibição inicial
$usuarios = Usuario::getAll();
$caronas = Carona::getAll();
$estatisticas_usuarios = [
    'total' => Usuario::contarPorTipo(),
    'alunos' => Usuario::contarPorTipo('aluno'),
    'gestores' => Usuario::contarPorTipo('gestor')
];
$estatisticas_caronas = Carona::getEstatisticas();

include 'includes/header.php';
?>

<body>


<main class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-cogs"></i> Painel Administrativo</h1>
            
            <!-- Cards de Estatísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $estatisticas_usuarios['total'] ?></h4>
                                    <p>Total Usuários</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $estatisticas_usuarios['alunos'] ?></h4>
                                    <p>Alunos</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-graduation-cap fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $estatisticas_caronas->total ?></h4>
                                    <p>Total Caronas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-car fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4><?= $estatisticas_caronas->ativas ?></h4>
                                    <p>Caronas Ativas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs de Navegação -->
            <ul class="nav nav-tabs" id="gestorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">
                        <i class="fas fa-users"></i> Gerenciar Usuários
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="caronas-tab" data-bs-toggle="tab" data-bs-target="#caronas" type="button" role="tab">
                        <i class="fas fa-car"></i> Gerenciar Caronas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="relatorios-tab" data-bs-toggle="tab" data-bs-target="#relatorios" type="button" role="tab">
                        <i class="fas fa-chart-bar"></i> Relatórios
                    </button>
                </li>
            </ul>

            <!-- Conteúdo das Tabs -->
            <div class="tab-content" id="gestorTabsContent">
                <!-- Tab Usuários -->
                <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-users"></i> Gerenciamento de Usuários</h5>
                            <button class="btn btn-primary" onclick="abrirModalUsuario()">
                                <i class="fas fa-plus"></i> Novo Usuário
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="filtro-usuario" placeholder="Buscar por nome, email ou matrícula...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="filtro-tipo-usuario">
                                        <option value="">Todos os tipos</option>
                                        <option value="aluno">Alunos</option>
                                        <option value="gestor">Gestores</option>
                                        <option value="admin">Administradores</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-primary" onclick="filtrarUsuarios()">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabela de Usuários -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Tipo</th>
                                            <th>Matrícula</th>
                                            <th>Status</th>
                                            <th>Data Cadastro</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabela-usuarios">
                                        <?php foreach ($usuarios as $user): ?>
                                        <tr>
                                            <td><?= $user->id ?></td>
                                            <td><?= htmlspecialchars($user->nome) ?></td>
                                            <td><?= htmlspecialchars($user->email) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user->tipo_usuario == 'admin' ? 'danger' : ($user->tipo_usuario == 'gestor' ? 'warning' : 'info') ?>">
                                                    <?= ucfirst($user->tipo_usuario) ?>
                                                </span>
                                            </td>
                                            <td><?= $user->matricula ?? '-' ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user->ativo ? 'success' : 'secondary' ?>">
                                                    <?= $user->ativo ? 'Ativo' : 'Inativo' ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($user->data_cadastro)) ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="editarUsuario(<?= $user->id ?>)" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-<?= $user->ativo ? 'warning' : 'success' ?>" 
                                                            onclick="alterarStatusUsuario(<?= $user->id ?>, <?= $user->ativo ? 0 : 1 ?>)" 
                                                            title="<?= $user->ativo ? 'Desativar' : 'Ativar' ?>">
                                                        <i class="fas fa-<?= $user->ativo ? 'ban' : 'check' ?>"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="deletarUsuario(<?= $user->id ?>)" title="Deletar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Caronas -->
                <div class="tab-pane fade" id="caronas" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-car"></i> Gerenciamento de Caronas</h5>
                            <button class="btn btn-primary" onclick="abrirModalCarona()">
                                <i class="fas fa-plus"></i> Nova Carona
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="filtro-origem" placeholder="Origem...">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" id="filtro-destino" placeholder="Destino...">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" id="filtro-data">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" id="filtro-status-carona">
                                        <option value="">Todos os status</option>
                                        <option value="ativa">Ativas</option>
                                        <option value="finalizada">Finalizadas</option>
                                        <option value="cancelada">Canceladas</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-primary" onclick="filtrarCaronas()">
                                        <i class="fas fa-search"></i> Filtrar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabela de Caronas -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Motorista</th>
                                            <th>Origem</th>
                                            <th>Destino</th>
                                            <th>Data/Hora</th>
                                            <th>Vagas</th>
                                            <th>Preço</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabela-caronas">
                                        <?php foreach ($caronas as $car): ?>
                                        <tr>
                                            <td><?= $car->id ?></td>
                                            <td><?= htmlspecialchars($car->motorista_nome) ?></td>
                                            <td><?= htmlspecialchars($car->origem) ?></td>
                                            <td><?= htmlspecialchars($car->destino) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($car->data_viagem . ' ' . $car->hora_viagem)) ?></td>
                                            <td><?= $car->vagas_disponiveis ?></td>
                                            <td><?= $car->preco ? 'R$ ' . number_format($car->preco, 2, ',', '.') : 'Gratuita' ?></td>
                                            <td>
                                                <span class="badge bg-<?= $car->status == 'ativa' ? 'success' : ($car->status == 'finalizada' ? 'primary' : 'danger') ?>">
                                                    <?= ucfirst($car->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="editarCarona(<?= $car->id ?>)" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger" onclick="deletarCarona(<?= $car->id ?>)" title="Deletar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Relatórios -->
                <div class="tab-pane fade" id="relatorios" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar"></i> Relatórios e Estatísticas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Usuários por Tipo</h6>
                                    <canvas id="graficoUsuarios" width="400" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <h6>Caronas por Status</h6>
                                    <canvas id="graficoCaronas" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal Usuário -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioTitulo">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formUsuario" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="usuario_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_nome" class="form-label">Nome *</label>
                                <input type="text" class="form-control" id="usuario_nome" name="nome" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="usuario_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="usuario_senha" name="senha">
                                <small class="text-muted">Deixe em branco para manter a senha atual (ao editar)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="usuario_telefone" name="telefone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_tipo" class="form-label">Tipo de Usuário *</label>
                                <select class="form-select" id="usuario_tipo" name="tipo_usuario" required>
                                    <option value="aluno">Aluno</option>
                                    <option value="gestor">Gestor</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="usuario_matricula" class="form-label">Matrícula</label>
                                <input type="text" class="form-control" id="usuario_matricula" name="matricula">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="usuario_foto" class="form-label">Foto de Perfil</label>
                        <input type="file" class="form-control" id="usuario_foto" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Carona -->
<div class="modal fade" id="modalCarona" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCaronaTitulo">Nova Carona</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCarona">
                <div class="modal-body">
                    <input type="hidden" id="carona_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carona_motorista" class="form-label">Motorista *</label>
                                <select class="form-select" id="carona_motorista" name="motorista_id" required>
                                    <option value="">Selecione o motorista</option>
                                    <?php foreach ($usuarios as $user): ?>
                                        <option value="<?= $user->id ?>"><?= htmlspecialchars($user->nome) ?> (<?= $user->email ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carona_status" class="form-label">Status</label>
                                <select class="form-select" id="carona_status" name="status">
                                    <option value="ativa">Ativa</option>
                                    <option value="finalizada">Finalizada</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carona_origem" class="form-label">Origem *</label>
                                <input type="text" class="form-control" id="carona_origem" name="origem" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carona_destino" class="form-label">Destino *</label>
                                <input type="text" class="form-control" id="carona_destino" name="destino" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="carona_data" class="form-label">Data da Viagem *</label>
                                <input type="date" class="form-control" id="carona_data" name="data_viagem" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="carona_hora" class="form-label">Hora da Viagem *</label>
                                <input type="time" class="form-control" id="carona_hora" name="hora_viagem" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="carona_vagas" class="form-label">Vagas Disponíveis *</label>
                                <input type="number" class="form-control" id="carona_vagas" name="vagas_disponiveis" min="1" max="8" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carona_preco" class="form-label">Preço (R$)</label>
                                <input type="number" class="form-control" id="carona_preco" name="preco" step="0.01" min="0">
                                <small class="text-muted">Deixe em branco para carona gratuita</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="carona_observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="carona_observacoes" name="observacoes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/gestor.js"></script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
