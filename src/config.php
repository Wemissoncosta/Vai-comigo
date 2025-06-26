<?php
// Configurações do site
define('SITE_NAME', 'VaiComigo');
define('SITE_URL', './');

// Configurações de banco de dados
define('DB_HOST', 'db');
define('DB_NAME', 'vaicomigo');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 em HTTPS

// Timezone
date_default_timezone_set('America/Sao_Paulo');
?>
