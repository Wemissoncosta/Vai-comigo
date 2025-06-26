<?php
$page_title = 'Cadastro de Carona';
$page_css = 'cadastro.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Conteúdo Principal -->
<div class="container">
    <div class="container-cadastro">
        <h2 class="titulo-centralizado mb-4">Cadastro de Carona</h2>
        
        <div id="map" class="map-container mb-4"></div>

        <form>
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
            
            <div class="row mb-3">
                <div class="col">
                    <label class="rotulo-formulario">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        Data
                    </label>
                    <input type="date" class="controle-formulario" value="2024-03-25" required>
                </div>
                <div class="col">
                    <label class="rotulo-formulario">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Horário
                    </label>
                    <input type="time" class="controle-formulario" value="18:00" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="rotulo-formulario">
                    <i class="fas fa-users text-primary me-2"></i>
                    Vagas Disponíveis
                </label>
                <input type="number" class="controle-formulario" value="3" min="1" max="4" required>
            </div>
            
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    Cadastrar
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='home.php'">
                    <i class="fas fa-times-circle me-2"></i>
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/main.js"></script>

<!-- Google Maps API -->
<script 
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAe9QW7c_3U4Z6FjozNJbnMtmjgjIHfx5g&callback=initMap&loading=async" 
    defer
></script>

</body>
</html>
