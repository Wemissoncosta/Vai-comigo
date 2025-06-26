<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="home.php">
            <i class="fas fa-car-side"></i>
            VaiComigo
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('home.php'); ?>" href="home.php">
                        <i class="fas fa-home"></i>
                        Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('cadastro.php'); ?>" href="cadastro.php">
                        <i class="fas fa-plus-circle"></i>
                        Cadastrar Carona
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('busca.php'); ?>" href="busca.php">
                        <i class="fas fa-search"></i>
                        Buscar Carona
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('carona-ativa.php'); ?>" href="carona-ativa.php">
                        <i class="fas fa-car"></i>
                        Carona Ativa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('chat.php'); ?>" href="chat.php">
                        <i class="fas fa-comments"></i>
                        Chat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('notificacoes.php'); ?>" href="notificacoes.php">
                        <i class="fas fa-bell"></i>
                        Notificações
                        <span class="badge bg-danger">3</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('perfil.php'); ?>" href="perfil.php">
                        <i class="fas fa-user-circle"></i>
                        Perfil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Sair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
