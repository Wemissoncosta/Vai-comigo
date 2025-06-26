<?php
$page_title = 'Chat';
$page_css = 'chat.css';
include 'includes/header.php';
?>
<body>
<?php include 'includes/menu.php'; ?>

<!-- Chat Container -->
<div class="container">
  <div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar">
      <div class="chat-search">
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            placeholder="Buscar conversa..."
            id="chatSearch"
          />
          <button class="btn btn-outline-secondary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
      <div class="chat-contacts" id="chatContacts">
        <!-- Contacts will be dynamically loaded here -->
        <div class="text-center text-muted p-3">Carregando contatos...</div>
      </div>
    </div>

    <!-- Main Chat -->
    <div class="chat-main">
      <div class="chat-header">
        <div class="d-flex align-items-center">
          <button
            class="btn btn-link d-lg-none me-2 p-0"
            onclick="toggleSidebar()"
          >
            <i class="fas fa-bars"></i>
          </button>
          <h5 class="mb-0" id="currentContact">Selecione um contato</h5>
        </div>
      </div>
      <div class="chat-messages" id="chatMessages">
        <!-- Messages will be dynamically loaded here -->
        <div class="text-center text-muted">
          Selecione um contato para iniciar uma conversa
        </div>
      </div>
      <div class="chat-input">
        <form id="messageForm" onsubmit="sendMessage(event)">
          <input
            type="text"
            class="form-control"
            placeholder="Digite sua mensagem..."
            id="messageInput"
            disabled
          />
          <button
            type="submit"
            class="btn btn-primary"
            disabled
            id="sendButton"
          >
            <i class="fas fa-paper-plane"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
