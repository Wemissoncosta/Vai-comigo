<?php
$page_title = 'Notificações';
$page_css = 'notificacoes.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Conteúdo Principal -->
<div class="container mt-4">
  <div class="container-notificacoes">
    <h2 class="mb-4">
      <i class="fas fa-bell text-primary"></i>
      Notificações
    </h2>

    <!-- Lista de Notificações -->
    <div class="lista-notificacoes">
      <!-- Notificação Não Lida -->
      <div class="notificacao nao-lida">
        <div class="icone-notificacao">
          <i class="fas fa-car-side text-primary"></i>
        </div>
        <div class="conteudo-notificacao">
          <h5>Nova solicitação de carona</h5>
          <p>Maria Santos solicitou uma vaga na sua carona para o Centro.</p>
          <small class="texto-tempo">Agora mesmo</small>
        </div>
        <button class="btn-marcar-lida" title="Marcar como lida">
          <i class="fas fa-check"></i>
        </button>
      </div>

      <!-- Notificação Não Lida -->
      <div class="notificacao nao-lida">
        <div class="icone-notificacao">
          <i class="fas fa-comments text-primary"></i>
        </div>
        <div class="conteudo-notificacao">
          <h5>Nova mensagem</h5>
          <p>João Silva enviou uma mensagem sobre a carona de hoje.</p>
          <small class="texto-tempo">5 minutos atrás</small>
        </div>
        <button class="btn-marcar-lida" title="Marcar como lida">
          <i class="fas fa-check"></i>
        </button>
      </div>

      <!-- Notificação Não Lida -->
      <div class="notificacao nao-lida">
        <div class="icone-notificacao">
          <i class="fas fa-star text-warning"></i>
        </div>
        <div class="conteudo-notificacao">
          <h5>Avaliação recebida</h5>
          <p>Você recebeu uma nova avaliação de carona.</p>
          <small class="texto-tempo">1 hora atrás</small>
        </div>
        <button class="btn-marcar-lida" title="Marcar como lida">
          <i class="fas fa-check"></i>
        </button>
      </div>

      <!-- Notificação Lida -->
      <div class="notificacao">
        <div class="icone-notificacao">
          <i class="fas fa-check-circle text-success"></i>
        </div>
        <div class="conteudo-notificacao">
          <h5>Carona confirmada</h5>
          <p>Sua carona para IFTO foi confirmada para amanhã às 07:30.</p>
          <small class="texto-tempo">2 horas atrás</small>
        </div>
      </div>
    </div>

    <!-- Botões de Ação -->
    <div class="acoes-notificacao mt-4">
      <button class="btn btn-primary">
        <i class="fas fa-check-double me-2"></i>
        Marcar todas como lidas
      </button>
      <button class="btn btn-outline-danger">
        <i class="fas fa-trash-alt me-2"></i>
        Limpar notificações
      </button>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
