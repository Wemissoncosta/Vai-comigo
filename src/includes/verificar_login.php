<?php
session_start();

/**
 * Verifica se o usuário está logado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_logado']) && !empty($_SESSION['usuario_logado']);
}

/**
 * Obtém os dados do usuário logado
 * @return array|null
 */
function getUsuarioLogado() {
    return $_SESSION['usuario_logado'] ?? null;
}

/**
 * Obtém o nome do usuário logado
 * @return string
 */
function getNomeUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario['nome'] ?? 'Usuário';
}

/**
 * Obtém o tipo do usuário logado
 * @return string
 */
function getTipoUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario['tipo_usuario'] ?? 'aluno';
}

/**
 * Obtém a foto do usuário logado
 * @return string|null
 */
function getFotoUsuario() {
    $usuario = getUsuarioLogado();
    return $usuario['foto_perfil'] ?? null;
}

/**
 * Verifica se o usuário é admin
 * @return bool
 */
function isAdmin() {
    return getTipoUsuario() === 'admin';
}

/**
 * Redireciona para login se não estiver logado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Redireciona para login se não for admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: home.php');
        exit;
    }
}

/**
 * Faz logout do usuário
 */
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Verificar se não está na página de login e se não está logado
$current_page = basename($_SERVER['PHP_SELF']);
$public_pages = ['login.php', 'index.php'];

if (!in_array($current_page, $public_pages) && !isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
