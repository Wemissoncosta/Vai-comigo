<?php
session_start();

// Se já está logado, redireciona para home
if (isset($_SESSION['usuario_logado'])) {
    header('Location: home.php');
    exit;
}

// Se não está logado, redireciona para o login
header('Location: login.php');
exit;
?>
