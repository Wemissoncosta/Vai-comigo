<?php
// Inicia a sessão se ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado'])) {
    // Se não estiver logado, redireciona para a página de login
    header('Location: login.php');
    exit;
}

// Função para obter dados do usuário logado
function getUsuarioLogado() {
    return $_SESSION['usuario_logado'] ?? null;
}

// Função para verificar se o usuário tem permissão (tipo de usuário)
function verificarPermissao($tiposPermitidos = []) {
    $usuario = getUsuarioLogado();
    
    if (empty($tiposPermitidos)) {
        return true; // Se não especificar tipos, qualquer usuário logado tem acesso
    }
    
    return in_array($usuario['tipo_usuario'], $tiposPermitidos);
}

// Função para obter nome do usuário para exibição
function getNomeUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['nome'] : 'Usuário';
}

// Função para obter foto do usuário
function getFotoUsuario() {
    $usuario = getUsuarioLogado();
    if ($usuario && $usuario['foto_perfil'] && file_exists("imagens/" . $usuario['foto_perfil'])) {
        return "imagens/" . $usuario['foto_perfil'];
    }
    return null;
}

// Função para verificar se é o próprio usuário
function isProprioUsuario($userId) {
    $usuario = getUsuarioLogado();
    return $usuario && $usuario['id'] == $userId;
}

// Função para verificar se é admin
function isAdmin() {
    $usuario = getUsuarioLogado();
    return $usuario && $usuario['tipo_usuario'] === 'admin';
}

// Função para verificar se é aluno
function isAluno() {
    $usuario = getUsuarioLogado();
    return $usuario && $usuario['tipo_usuario'] === 'aluno';
}

// Função para obter a matrícula do usuário
function getMatriculaUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['matricula'] : null;
}

// Função para obter o tipo de usuário
function getTipoUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['tipo_usuario'] : null;
}

// Função para obter o email do usuário
function getEmailUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['email'] : null;
}

// Função para obter o ID do usuário
function getIdUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['id'] : null;
}

// Função para obter o telefone do usuário
function getTelefoneUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario ? $usuario['telefone'] : null;
}
?>
