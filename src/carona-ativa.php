<?php
$page_title = 'Carona Ativa';
$page_css = 'cadastro.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Conteúdo Principal -->
<div class="container">
    <div class="container-cadastro">
        <h1 class="titulo-centralizado mb-4">Carona Ativa</h1>

        <section class="my-4 p-4 border rounded shadow-sm bg-white">
            <h2>Detalhes da Carona</h2>
            <p><strong>Origem:</strong> IFTO Campus Colinas</p>
            <p><strong>Destino:</strong> Centro</p>
            <p><strong>Data e Hora:</strong> 25/03/2024 às 18:00</p>
            <p><strong>Status:</strong> Em andamento</p>
        </section>

        <section class="participants my-4">
            <div class="row">
                <div class="col-md-6 participant-card p-3 border rounded shadow-sm">
                    <h3><i class="fas fa-user-tie"></i> Motorista</h3>
                    <p><strong>Nome:</strong> João Silva</p>
                    <p><strong>Contato:</strong> joao.silva@ifto.edu.br</p>
                    <p><strong>Avaliação:</strong> 4.8 / 5</p>
<button class="btn btn-outline-primary botao-solicitar-carona">
  Chat com Motorista
</button>
                </div>
                <div class="col-md-6 participant-card p-3 border rounded shadow-sm">
                    <h3><i class="fas fa-user"></i> Passageiro</h3>
                    <p><strong>Nome:</strong> Maria Santos</p>
                    <p><strong>Contato:</strong> maria.santos@ifto.edu.br</p>
                    <p><strong>Avaliação:</strong> 4.7 / 5</p>
<button class="btn btn-outline-primary botao-solicitar-carona">
  Chat com Passageiro
</button>
                </div>
            </div>
        </section>

        <section class="actions my-4 d-flex gap-3">
            <button class="btn btn-success flex-fill" onclick="showFinalizarModal()">
                <i class="fas fa-check-circle me-2"></i>
                Finalizar Carona
            </button>
            <button class="btn btn-warning flex-fill" onclick="showAvaliacaoModal()">
                <i class="fas fa-star me-2"></i>
                Avaliar Carona
            </button>
        </section>
    </div>
</div>

<!-- Modal de Avaliação -->
<div class="modal fade" id="avaliacaoModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Avaliar Carona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="avaliacaoForm">
          <div class="mb-3">
            <label class="form-label">Avalie sua experiência</label>
            <div class="rating">
              <i class="fas fa-star fs-3 text-warning" data-rating="1"></i>
              <i class="fas fa-star fs-3 text-warning" data-rating="2"></i>
              <i class="fas fa-star fs-3 text-warning" data-rating="3"></i>
              <i class="fas fa-star fs-3 text-warning" data-rating="4"></i>
              <i class="fas fa-star fs-3 text-warning" data-rating="5"></i>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Comentário (opcional)</label>
            <textarea class="form-control" rows="3" placeholder="Deixe seu comentário sobre a carona..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="submitAvaliacao()">Enviar Avaliação</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Finalização -->
<div class="modal fade" id="finalizarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Finalizar Carona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Você está prestes a finalizar esta carona. Isso não pode ser desfeito.</p>
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>
          Após finalizar, você poderá avaliar a carona.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="finalizarCarona()">Confirmar Finalização</button>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

