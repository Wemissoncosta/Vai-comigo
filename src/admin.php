<?php
session_start();
require_once __DIR__ . "/Classes/Usuario.php";

$page_title = 'Painel Administrativo - CRUD Usuários';
$page_css = 'style.css';

// Verificar se o usuário está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']->tipo_usuario !== 'admin') {
    header('Location: index.php');
    exit;
}

try {
    $totalUsuarios = Usuario::contarPorTipo();
    $totalAdmins = Usuario::contarPorTipo('admin');
    $totalAlunos = Usuario::contarPorTipo('aluno');
    
    $usuariosRecentes = Usuario::buscar('', '');
    $usuariosRecentes = array_slice($usuariosRecentes, 0, 5); // Últimos 5 usuários
} catch (Exception $e) {
    $erro = 'Erro ao carregar dados: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title;?> - VaiComigo</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/<?php echo $page_css;?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="imagens/favicon.ico">
    
    <style>
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <div class="text-center mb-4">
                    <h4 class="text-white">
                        <i class="fas fa-users-cog me-2"></i>
                        Admin Panel
                    </h4>
                    <small class="text-white-50">VaiComigo CRUD</small>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="admin.php">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                    <a class="nav-link" href="list.php">
                        <i class="fas fa-users me-2"></i>
                        Gerenciar Usuários
                    </a>
                    <a class="nav-link" href="create.php">
                        <i class="fas fa-user-plus me-2"></i>
                        Novo Usuário
                    </a>
                    <hr class="text-white-50">
                    <a class="nav-link" href="home.php">
                        <i class="fas fa-home me-2"></i>
                        Voltar ao Sistema
                    </a>
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Sair
                    </a>
                </nav>
                
                <div class="mt-auto pt-4">
                    <div class="text-center">
                        <small class="text-white-50">
                            Logado como:<br>
                            <strong class="text-white"><?= htmlspecialchars($_SESSION['usuario']->nome) ?></strong>
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard - CRUD Usuários
                    </h2>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        <?= date('d/m/Y H:i') ?>
                    </div>
                </div>
                
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= $erro ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Cards de Estatísticas -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card stats-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-primary mb-0"><?= $totalUsuarios ?? 0 ?></h3>
                                        <small class="text-muted">Total de Usuários</small>
                                    </div>
                                    <div class="text-primary">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stats-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-danger mb-0"><?= $totalAdmins ?? 0 ?></h3>
                                        <small class="text-muted">Administradores</small>
                                    </div>
                                    <div class="text-danger">
                                        <i class="fas fa-user-shield fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="card stats-card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="text-success mb-0"><?= $totalAlunos ?? 0 ?></h3>
                                        <small class="text-muted">Alunos</small>
                                    </div>
                                    <div class="text-success">
                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ações Rápidas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-bolt me-2"></i>
                                    Ações Rápidas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <a href="create.php" class="btn btn-primary w-100">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Novo Usuário
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="list.php" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-list me-2"></i>
                                            Listar Todos
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="list.php?tipo=admin" class="btn btn-outline-danger w-100">
                                            <i class="fas fa-user-shield me-2"></i>
                                            Ver Admins
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="list.php?tipo=aluno" class="btn btn-outline-success w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Ver Alunos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Usuários Recentes -->
                <?php if (!empty($usuariosRecentes)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Usuários Recentes
                                </h5>
                                <a href="list.php" class="btn btn-sm btn-outline-primary">
                                    Ver Todos
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Tipo</th>
                                                <th>Status</th>
                                                <th>Cadastro</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($usuariosRecentes as $usuario): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($usuario->foto_perfil): ?>
                                                                <img src="imagens/<?= $usuario->foto_perfil ?>" 
                                                                     class="rounded-circle me-2" 
                                                                     width="24" height="24" 
                                                                     alt="Foto">
                                                            <?php else: ?>
                                                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                                     style="width: 24px; height: 24px;">
                                                                    <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?= htmlspecialchars($usuario->nome) ?>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($usuario->email) ?></td>
                                                    <td>
                                                        <?php
                                                        $badges = [
                                                            'admin' => 'bg-danger',
                                                            'aluno' => 'bg-primary'
                                                        ];
                                                        $badge = $badges[$usuario->tipo_usuario] ?? 'bg-secondary';
                                                        ?>
                                                        <span class="badge <?= $badge ?>">
                                                            <?= ucfirst($usuario->tipo_usuario) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($usuario->ativo): ?>
                                                            <span class="badge bg-success">Ativo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Inativo</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($usuario->data_cadastro)) ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="visualize.php?id=<?= $usuario->id ?>" 
                                                               class="btn btn-sm btn-outline-info" 
                                                               title="Visualizar">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="edit.php?id=<?= $usuario->id ?>" 
                                                               class="btn btn-sm btn-outline-warning" 
                                                               title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
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
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
