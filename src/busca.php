<?php
$page_title = 'Busca de Carona';
$page_css = 'busca.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Conteúdo Principal -->
<div class="container">
    <div class="container-cadastro">
        <h2 class="titulo-centralizado mb-4">Buscar Carona</h2>

        <div id="map" class="map-container mb-4"></div>

        <!-- Formulário de Busca -->
        <form class="formulario-busca mb-4">
            <div class="mb-3">
                <label class="rotulo-formulario">
                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                    Local de Partida
                </label>
                <input type="text" class="controle-formulario" value="IFTO Campus Colinas" required>
            </div>

            <div class="mb-3">
                <label class="rotulo-formulario">
                    <i class="fas fa-location-dot text-primary me-2"></i>
                    Destino
                </label>
                <input type="text" class="controle-formulario" value="Centro" required>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="rotulo-formulario">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        Data
                    </label>
                    <input type="date" class="controle-formulario" value="2024-03-25" required>
                </div>
                <div class="col-md-6">
                    <label class="rotulo-formulario">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Horário
                    </label>
                    <input type="time" class="controle-formulario" value="18:00" required>
                </div>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>
                    Buscar Carona
                </button>
            </div>
        </form>

        <!-- Resultados da Busca -->
        <div class="resultados-busca">
            <h3 class="titulo-secao mb-4">
                <i class="fas fa-list-ul text-primary me-2"></i>
                Caronas Disponíveis
            </h3>

            <div class="cartao-carona">
                <h5 class="titulo-cartao">Maria Santos</h5>
                <p class="texto-cartao">
                    <i class="fas fa-map-marker-alt"></i> De: IFTO Campus Colinas
                </p>
                <p class="texto-cartao">
                    <i class="fas fa-location-dot"></i> Para: Centro
                </p>
                <p class="texto-cartao">
                    <i class="fas fa-calendar"></i> 25/03/2024 às 18:00
                </p>
                <span class="badge bg-success">3 vagas</span>
                <button class="btn btn-outline-primary botao-solicitar-carona">Solicitar Carona</button>
            </div>
            <div class="cartao-carona">
                <h5 class="titulo-cartao">Pedro Oliveira</h5>
                <p class="texto-cartao">
                    <i class="fas fa-map-marker-alt"></i> De: Centro
                </p>
                <p class="texto-cartao">
                    <i class="fas fa-location-dot"></i> Para: IFTO Campus Colinas
                </p>
                <p class="texto-cartao">
                    <i class="fas fa-calendar"></i> 26/03/2024 às 07:30
                </p>
                <span class="badge bg-success">2 vagas</span>
                <button class="btn btn-outline-primary botao-solicitar-carona">Solicitar Carona</button>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/main.js"></script>

<!-- Google Maps API -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAe9QW7c_3U4Z6FjozNJbnMtmjgjIHfx5g&callback=initMap&loading=async"
    defer></script>
</body>
</html>
