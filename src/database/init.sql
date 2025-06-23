-- Criação do banco de dados VaiComigo
CREATE DATABASE IF NOT EXISTS vaicomigo;
USE vaicomigo;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    foto_perfil VARCHAR(255),
    avaliacao DECIMAL(3,2) DEFAULT 0.00,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela de caronas
CREATE TABLE IF NOT EXISTS caronas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    motorista_id INT NOT NULL,
    origem VARCHAR(255) NOT NULL,
    destino VARCHAR(255) NOT NULL,
    data_viagem DATE NOT NULL,
    hora_viagem TIME NOT NULL,
    vagas_disponiveis INT NOT NULL,
    preco DECIMAL(10,2),
    observacoes TEXT,
    status ENUM('ativa', 'cancelada', 'finalizada') DEFAULT 'ativa',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (motorista_id) REFERENCES usuarios(id)
);

-- Tabela de solicitações de carona
CREATE TABLE IF NOT EXISTS solicitacoes_carona (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carona_id INT NOT NULL,
    passageiro_id INT NOT NULL,
    status ENUM('pendente', 'aceita', 'recusada', 'cancelada') DEFAULT 'pendente',
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carona_id) REFERENCES caronas(id),
    FOREIGN KEY (passageiro_id) REFERENCES usuarios(id)
);

-- Tabela de mensagens do chat
CREATE TABLE IF NOT EXISTS mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carona_id INT NOT NULL,
    usuario_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (carona_id) REFERENCES caronas(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de notificações
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo ENUM('solicitacao', 'aceite', 'recusa', 'cancelamento', 'mensagem') NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de avaliações
CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    avaliador_id INT NOT NULL,
    avaliado_id INT NOT NULL,
    carona_id INT NOT NULL,
    nota INT CHECK (nota >= 1 AND nota <= 5),
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (avaliador_id) REFERENCES usuarios(id),
    FOREIGN KEY (avaliado_id) REFERENCES usuarios(id),
    FOREIGN KEY (carona_id) REFERENCES caronas(id)
);

-- Inserir dados de exemplo
INSERT INTO usuarios (nome, email, senha, telefone) VALUES
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 99999-9999'),
('Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 88888-8888'),
('Pedro Oliveira', 'pedro@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 77777-7777');

INSERT INTO caronas (motorista_id, origem, destino, data_viagem, hora_viagem, vagas_disponiveis, preco, observacoes) VALUES
(1, 'São Paulo - SP', 'Campinas - SP', '2024-01-15', '08:00:00', 3, 25.00, 'Viagem tranquila, saída pontual'),
(2, 'Rio de Janeiro - RJ', 'Niterói - RJ', '2024-01-16', '07:30:00', 2, 15.00, 'Carro confortável com ar condicionado'),
(3, 'Belo Horizonte - MG', 'Contagem - MG', '2024-01-17', '18:00:00', 4, 20.00, 'Volta do trabalho');

INSERT INTO notificacoes (usuario_id, titulo, mensagem, tipo) VALUES
(1, 'Nova solicitação', 'Maria Santos solicitou uma vaga na sua carona', 'solicitacao'),
(2, 'Solicitação aceita', 'Sua solicitação foi aceita por João Silva', 'aceite'),
(3, 'Nova mensagem', 'Você tem uma nova mensagem no chat', 'mensagem');
