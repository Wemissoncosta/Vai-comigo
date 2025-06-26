<?php

require_once __DIR__ . "/BD.php";

class Carona {
    
    /**
     * Busca todas as caronas
     * @return array
     */
    public static function getAll() {
        $conn = BD::getConnection();
        
        $sql = $conn->query("
            SELECT c.*, u.nome as motorista_nome 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            ORDER BY c.data_viagem DESC, c.hora_viagem DESC
        ");
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Busca carona por ID
     * @param int $id
     * @return object|false
     */
    public static function getById($id) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("
            SELECT c.*, u.nome as motorista_nome, u.email as motorista_email 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            WHERE c.id = :id
        ");
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Busca caronas por motorista
     * @param int $motorista_id
     * @return array
     */
    public static function getByMotorista($motorista_id) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("
            SELECT c.*, u.nome as motorista_nome 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            WHERE c.motorista_id = :motorista_id 
            ORDER BY c.data_viagem DESC, c.hora_viagem DESC
        ");
        $sql->bindValue(":motorista_id", $motorista_id);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Busca caronas por status
     * @param string $status
     * @return array
     */
    public static function getByStatus($status) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("
            SELECT c.*, u.nome as motorista_nome 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            WHERE c.status = :status 
            ORDER BY c.data_viagem DESC, c.hora_viagem DESC
        ");
        $sql->bindValue(":status", $status);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Função para inserir carona no banco
     * @param int $motorista_id
     * @param string $origem
     * @param string $destino
     * @param string $data_viagem
     * @param string $hora_viagem
     * @param int $vagas_disponiveis
     * @param string $observacoes
     * @return int
     */
    public function inserir($motorista_id, $origem, $destino, $data_viagem, $hora_viagem, $vagas_disponiveis, $observacoes = null) {
        $conn = BD::getConnection();
        
        // Valida se a data não é no passado
        if (strtotime($data_viagem) < strtotime(date('Y-m-d'))) {
            throw new Exception("Data da viagem não pode ser no passado");
        }
        
        // Executa o sql de inserção
        $sql = $conn->prepare("
            INSERT INTO caronas(motorista_id, origem, destino, data_viagem, hora_viagem, vagas_disponiveis, observacoes) 
            VALUES (:motorista_id, :origem, :destino, :data_viagem, :hora_viagem, :vagas_disponiveis, :observacoes)
        ");
        $sql->bindValue(":motorista_id", $motorista_id);
        $sql->bindValue(":origem", $origem);
        $sql->bindValue(":destino", $destino);
        $sql->bindValue(":data_viagem", $data_viagem);
        $sql->bindValue(":hora_viagem", $hora_viagem);
        $sql->bindValue(":vagas_disponiveis", $vagas_disponiveis);
        $sql->bindValue(":observacoes", $observacoes);
        $sql->execute();
        
        // Retorna o ID da carona criada
        return $conn->lastInsertId();
    }

    /**
     * Função para atualizar a carona
     * @param int $id
     * @param string $origem
     * @param string $destino
     * @param string $data_viagem
     * @param string $hora_viagem
     * @param int $vagas_disponiveis
     * @param string $observacoes
     * @param string $status
     * @return bool
     */
    public function atualizar($id, $origem, $destino, $data_viagem, $hora_viagem, $vagas_disponiveis, $observacoes = null, $status = 'ativa') {
        $conn = BD::getConnection();
        
        // Consulta ao BD
        $carona = self::getById($id);
        
        if (!$carona) {
            throw new Exception("Carona não encontrada");
        }
        
        // Valida se a data não é no passado (apenas se não estiver finalizada)
        if ($status !== 'finalizada' && strtotime($data_viagem) < strtotime(date('Y-m-d'))) {
            throw new Exception("Data da viagem não pode ser no passado");
        }
        
        $sql = $conn->prepare("
            UPDATE caronas SET 
                origem = :origem, 
                destino = :destino, 
                data_viagem = :data_viagem, 
                hora_viagem = :hora_viagem, 
                vagas_disponiveis = :vagas_disponiveis, 
                observacoes = :observacoes,
                status = :status
            WHERE id = :id
        ");
        
        $sql->bindValue(":origem", $origem);
        $sql->bindValue(":destino", $destino);
        $sql->bindValue(":data_viagem", $data_viagem);
        $sql->bindValue(":hora_viagem", $hora_viagem);
        $sql->bindValue(":vagas_disponiveis", $vagas_disponiveis);
        $sql->bindValue(":observacoes", $observacoes);
        $sql->bindValue(":status", $status);
        $sql->bindValue(":id", $id);
        
        return $sql->execute();
    }

    /**
     * Função para deletar carona
     * @param int $id
     * @return bool
     */
    public function deletar($id) {
        $conn = BD::getConnection();
        
        // Primeiro deleta as solicitações relacionadas
        $sql = $conn->prepare("DELETE FROM solicitacoes_carona WHERE carona_id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        // Depois deleta as mensagens relacionadas
        $sql = $conn->prepare("DELETE FROM mensagens WHERE carona_id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        // Por fim deleta a carona
        $sql = $conn->prepare("DELETE FROM caronas WHERE id = :id");
        $sql->bindValue(":id", $id);
        
        return $sql->execute();
    }

    /**
     * Função para alterar status da carona
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function alterarStatus($id, $status) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("UPDATE caronas SET status = :status WHERE id = :id");
        $sql->bindValue(":status", $status);
        $sql->bindValue(":id", $id);
        
        return $sql->execute();
    }

    /**
     * Busca caronas com filtros
     * @param string $origem
     * @param string $destino
     * @param string $data
     * @param string $status
     * @return array
     */
    public static function buscar($origem = '', $destino = '', $data = '', $status = '') {
        $conn = BD::getConnection();
        
        $sql = "
            SELECT c.*, u.nome as motorista_nome 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            WHERE 1=1
        ";
        $params = [];
        
        if (!empty($origem)) {
            $sql .= " AND c.origem LIKE :origem";
            $params[':origem'] = "%$origem%";
        }
        
        if (!empty($destino)) {
            $sql .= " AND c.destino LIKE :destino";
            $params[':destino'] = "%$destino%";
        }
        
        if (!empty($data)) {
            $sql .= " AND c.data_viagem = :data";
            $params[':data'] = $data;
        }
        
        if (!empty($status)) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY c.data_viagem DESC, c.hora_viagem DESC";
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Conta total de caronas por status
     * @param string $status
     * @return int
     */
    public static function contarPorStatus($status = '') {
        $conn = BD::getConnection();
        
        $sql = "SELECT COUNT(*) FROM caronas WHERE 1=1";
        $params = [];
        
        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * Busca caronas disponíveis (ativas com vagas)
     * @return array
     */
    public static function getDisponiveis() {
        $conn = BD::getConnection();
        
        $sql = $conn->query("
            SELECT c.*, u.nome as motorista_nome 
            FROM caronas c 
            INNER JOIN usuarios u ON c.motorista_id = u.id 
            WHERE c.status = 'ativa' 
            AND c.vagas_disponiveis > 0 
            AND c.data_viagem >= CURDATE()
            ORDER BY c.data_viagem ASC, c.hora_viagem ASC
        ");
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Busca estatísticas das caronas
     * @return object
     */
    public static function getEstatisticas() {
        $conn = BD::getConnection();
        
        $stats = new stdClass();
        
        // Total de caronas
        $sql = $conn->query("SELECT COUNT(*) FROM caronas");
        $stats->total = $sql->fetchColumn();
        
        // Caronas ativas
        $sql = $conn->query("SELECT COUNT(*) FROM caronas WHERE status = 'ativa'");
        $stats->ativas = $sql->fetchColumn();
        
        // Caronas finalizadas
        $sql = $conn->query("SELECT COUNT(*) FROM caronas WHERE status = 'finalizada'");
        $stats->finalizadas = $sql->fetchColumn();
        
        // Caronas canceladas
        $sql = $conn->query("SELECT COUNT(*) FROM caronas WHERE status = 'cancelada'");
        $stats->canceladas = $sql->fetchColumn();
        
        // Caronas hoje
        $sql = $conn->query("SELECT COUNT(*) FROM caronas WHERE data_viagem = CURDATE()");
        $stats->hoje = $sql->fetchColumn();
        
        return $stats;
    }
}
