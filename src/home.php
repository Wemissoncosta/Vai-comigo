<?php
$page_title = 'Página Inicial';
$page_css = 'home.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Conteúdo Principal -->
    <div class="container">
        <div class="container-cadastro">
            <!-- Cabeçalho de Boas-Vindas -->
            <div class="cabecalho-boas-vindas">
            <div class="avatar-usuario">
                <i class="fas fa-user"></i>
            </div>
            <h2>João Silva</h2>
            <p class="mb-3">Aluno</p>
            
            <div class="container-estatisticas">
                <div class="item-estatistica">
                    <div class="numero-estatistica">5</div>
                    <div class="rotulo-estatistica">Caronas Ativas</div>
                </div>
                <div class="item-estatistica">
                    <div class="numero-estatistica">20</div>
                    <div class="rotulo-estatistica">Usuários</div>
                </div>
                <div class="item-estatistica">
                    <div class="numero-estatistica">15</div>
                    <div class="rotulo-estatistica">Trajetos</div>
                </div>
            </div>

            <div class="botoes-acao">
                <a href="cadastro.php" class="btn btn-light btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>
                    Oferecer Carona
                </a>
                <a href="busca.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-search me-2"></i>
                    Buscar Carona
                </a>
            </div>
        </div>

        <!-- Seção de Caronas Recentes -->
        <div class="secao-conteudo">
            <h3 class="titulo-secao">
                <i class="fas fa-clock"></i>
                Caronas Recentes
            </h3>
            <div class="caronas-recentes">
                <div class="cartao-carona">
                    <div class="info-carona">
                        <div class="cabecalho-carona">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle"></i>
                                Maria Santos
                            </h5>
                            <span class="badge bg-success">
                                <i class="fas fa-users me-1"></i>
                                3 vagas
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt"></i>
                                De: IFTO Campus Colinas
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-location-dot"></i>
                                Para: Centro
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-calendar"></i>
                                25/03/2024 às 18:00
                            </p>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="busca.php" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="cartao-carona">
                    <div class="info-carona">
                        <div class="cabecalho-carona">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle"></i>
                                Pedro Oliveira
                            </h5>
                            <span class="badge bg-success">
                                <i class="fas fa-users me-1"></i>
                                2 vagas
                            </span>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt"></i>
                                De: Centro
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-location-dot"></i>
                                Para: IFTO Campus Colinas
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-calendar"></i>
                                26/03/2024 às 07:30
                            </p>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="busca.php" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
