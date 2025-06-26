<?php
$page_title = 'Perfil';
$page_css = 'perfil.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Container do Perfil -->
<div class="container">
  <div class="container-perfil">
    <!-- Cabeçalho do Perfil -->
    <div class="cabecalho-perfil">
      <div class="avatar-perfil">
        <i class="fas fa-user"></i>
      </div>
      <h2>João Silva</h2>
      <p class="mb-0">Matrícula: 202310001</p>
      <div class="estatisticas-perfil">
        <div class="estatistica">
          <div class="numero-estatistica">15</div>
          <div class="rotulo-estatistica">Caronas</div>
        </div>
        <div class="estatistica">
          <div class="numero-estatistica">4.8</div>
          <div class="rotulo-estatistica">Avaliação</div>
        </div>
        <div class="estatistica">
          <div class="numero-estatistica">98%</div>
          <div class="rotulo-estatistica">Pontualidade</div>
        </div>
      </div>
    </div>

    <!-- Conteúdo do Perfil -->
    <div class="conteudo-perfil">
      <!-- Seção de Informações Pessoais -->
      <div class="secao-perfil">
        <h3 class="titulo-secao">
          <i class="fas fa-user"></i>
          Informações Pessoais
        </h3>
        <form>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nome Completo</label>
              <input
                type="text"
                class="form-control"
                value="João Silva"
                required
              />
            </div>
            <div class="col-md-6">
              <label class="form-label">E-mail</label>
              <input
                type="email"
                class="form-control"
                value="joao.silva@estudante.ifto.edu.br"
                readonly
              />
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Curso</label>
              <input
                type="text"
                class="form-control"
                value="Engenharia de Software"
                required
              />
            </div>
            <div class="col-md-6">
              <label class="form-label">Período</label>
              <input type="text" class="form-control" value="5º" required />
            </div>
          </div>
          <button type="button" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>
            Salvar Alterações
          </button>
        </form>
      </div>

      <!-- Seção de Notificações -->
      <div class="secao-perfil">
        <h3 class="titulo-secao">
          <i class="fas fa-bell"></i>
          Notificações
        </h3>
        <div class="lista-notificacoes">
          <div class="notificacao">
            <i class="fas fa-car"></i>
            <div class="conteudo-notificacao">
              <div>Nova solicitação de carona para Centro</div>
              <div class="hora-notificacao">12/05/2024 14:30</div>
            </div>
          </div>
          <div class="notificacao">
            <i class="fas fa-comments"></i>
            <div class="conteudo-notificacao">
              <div>Nova mensagem recebida</div>
              <div class="hora-notificacao">12/05/2024 15:00</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seção de Histórico de Caronas -->
      <div class="secao-perfil">
        <h3 class="titulo-secao">
          <i class="fas fa-history"></i>
          Histórico de Caronas
        </h3>
        <div class="historico-caronas">
          <div class="cartao-carona">
            <div class="cabecalho-carona">
              <div class="data-carona">25/03/2024 às 18:00</div>
              <div class="avaliacao-carona">Motorista</div>
            </div>
            <div>De: IFTO Campus Colinas</div>
            <div class="text-muted">Para: Centro</div>
          </div>
          <div class="cartao-carona">
            <div class="cabecalho-carona">
              <div class="data-carona">26/03/2024 às 07:30</div>
              <div class="avaliacao-carona">Passageiro</div>
            </div>
            <div>De: Centro</div>
            <div class="text-muted">Para: IFTO Campus Colinas</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
