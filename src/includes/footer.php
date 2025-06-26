</main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <img src="<?php echo SITE_URL; ?>imagens/logo-final.svg" alt="VaiComigo" height="30" class="me-2">
                    <small class="text-muted">
                        IFTO - Campus Colinas do Tocantins
                    </small>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <small class="text-muted">
                        <?php if (isset($_SESSION['usuario_logado'])): ?>
                            Logado como: <?php echo getNomeUsuario(); ?> 
                            (<?php echo getTipoUsuario(); ?>)
                            | <a href="<?php echo SITE_URL; ?>logout.php" class="text-decoration-none">Sair</a>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    
    <!-- Main JS -->
    <script src="<?php echo SITE_URL; ?>js/main.js"></script>

    <?php if (isset($page_js)): ?>
    <!-- Page Specific JS -->
    <script src="<?php echo SITE_URL; ?>js/<?php echo $page_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
