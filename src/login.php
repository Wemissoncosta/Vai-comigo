<?php
session_start();
require_once 'Classes/Usuario.php';

$page_title = 'Entrar';
$page_css = 'style-login.css';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'] ?? '';
    $senha = $_POST['password'] ?? '';
    
    if (empty($matricula) || empty($senha)) {
        $erro = 'Matrícula e senha são obrigatórios';
    } else {
        try {
            $usuario = Usuario::autenticarPorMatricula($matricula, $senha);
            
            if ($usuario) {
                // Guarda dados do usuário na sessão
                $_SESSION['usuario_logado'] = [
                    'id' => $usuario->id,
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'tipo_usuario' => $usuario->tipo_usuario,
                    'foto_perfil' => $usuario->foto_perfil,
                    'matricula' => $usuario->matricula,
                    'telefone' => $usuario->telefone
                ];
                
                // Redireciona conforme tipo de usuário
                switch ($usuario->tipo_usuario) {
                    case 'admin':
                        header('Location: gestor.php');
                        break;
                    case 'aluno':
                    default:
                        header('Location: home.html');
                        break;
                }
                exit;
            } else {
                $erro = 'Matrícula ou senha inválidos';
            }
        } catch (Exception $e) {
            $erro = 'Erro ao fazer login: ' . $e->getMessage();
        }
    }
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
</head>
<body>
    <!-- Conteúdo principal -->
    <div class="container">
        <div class="container-login">
            <!-- Logo do sistema -->
            <div class="container-logo">
                <img src="imagens/logo-final.svg" alt="VaiComigo" class="imagem-logo">
                <p class="texto-muted">IFTO - Campus Colinas do Tocantins</p>
            </div>
            
            <?php if ($erro): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= $erro ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Formulário de login -->
            <form id="formulario-login" class="formulario-login" method="POST">
                <div class="mb-4">
                    <label class="rotulo-formulario">
                        <i class="fas fa-id-card text-primary me-2"></i>
                        Matrícula
                    </label>
                    <input 
                        type="text" 
                        name="matricula"
                        class="controle-formulario" 
                        placeholder="Digite sua matrícula" 
                        pattern=".*"
                        value="<?= htmlspecialchars($_POST['matricula'] ?? '') ?>"
                        autocomplete="username"
                        autofocus
                        tabindex="1"
                        required
                    >
                    <div class="texto-formulario">Ex: 202310000001</div>
                </div>
                
                <div class="mb-4">
                    <label class="rotulo-formulario">
                        <i class="fas fa-lock text-primary me-2"></i>
                        Senha
                    </label>
                    <div class="grupo-input">
                        <input 
                            type="password"
                            name="password" 
                            class="controle-formulario" 
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            tabindex="2"
                            required
                        >
                        <!-- Botão para mostrar/ocultar senha -->
                        <button 
                            class="btn btn-outline-secondary" 
                            type="button"
                            onclick="togglePassword(this)"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" tabindex="3">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Entrar
                    </button>
                </div>
                
                <a href="#" class="esqueceu-senha" type="button" onclick="showRecoveryModal()">
                    Esqueceu sua senha?
                </a>
            </form>
        </div>
    </div>

    <!-- Modal de recuperação de senha -->
    <div class="modal fade" id="recoveryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="recoveryForm">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-id-card text-primary me-2"></i>
                                Matrícula
                            </label>
                            <input 
                                type="text"
                                name="matricula" 
                                class="form-control" 
                                pattern="\d{12}" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                E-mail Institucional
                            </label>
                            <input 
                                type="email"
                                name="email" 
                                class="form-control" 
                                pattern=".*@estudante\.ifto\.edu\.br"
                                required
                            >
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitRecovery()">
                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="js/main.js"></script>
</body>
</html>
