<?php

require_once __DIR__ . "/BD.php";

class Usuario {
    
    const uploadDir = "imagens/";
    const tiposPermitidos = ["image/jpeg", "image/png"];
    const maxSize = 5 * 1024 * 1024; // 5MB

    // [Mantendo todos os métodos existentes...]
    
    /**
     * Função para autenticar usuário por matrícula
     * @param string $matricula
     * @param string $senha
     * @return object|false
     */
    public static function autenticarPorMatricula($matricula, $senha) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("SELECT * FROM usuarios WHERE matricula = :matricula AND ativo = 1");
        $sql->bindValue(":matricula", $matricula);
        $sql->execute();
        
        $usuario = $sql->fetch(PDO::FETCH_OBJ);
        
        if ($usuario && self::verificarSenha($senha, $usuario->senha)) {
            // Registra o último login
            self::registrarLogin($usuario->id);
            return $usuario;
        }
        
        return false;
    }

    /**
     * Registra a data e hora do último login do usuário
     * @param int $userId
     * @return bool
     */
    private static function registrarLogin($userId) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id");
        $sql->bindValue(":id", $userId);
        
        return $sql->execute();
    }

    // [Mantendo todos os outros métodos existentes...]

    public static function getAll() {
        $conn = BD::getConnection();
        
        $sql = $conn->query("SELECT * FROM usuarios ORDER BY nome");
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($id) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public static function getByTipo($tipo) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("SELECT id, nome, email, telefone, matricula, ativo, data_cadastro FROM usuarios WHERE tipo_usuario = :tipo ORDER BY nome");
        $sql->bindValue(":tipo", $tipo);
        $sql->execute();
        
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function inserir($nome, $email, $senha, $telefone = null, $tipo_usuario = 'aluno', $matricula = null, $foto = null) {
        $conn = BD::getConnection();
        
        if (self::emailExiste($email)) {
            throw new Exception("Email já cadastrado no sistema");
        }
        
        if (!empty($matricula) && self::matriculaExiste($matricula)) {
            throw new Exception("Matrícula já cadastrada no sistema");
        }
        
        $hash = self::hashSenha($senha);
        
        if (is_array($foto) && is_uploaded_file($foto['tmp_name'])) {
            $foto = self::uploadFoto($foto);
        }
        
        $sql = $conn->prepare("INSERT INTO usuarios(nome, email, senha, telefone, tipo_usuario, matricula, foto_perfil) VALUES (:nome, :email, :senha, :telefone, :tipo_usuario, :matricula, :foto_perfil)");
        $sql->bindValue(":nome", $nome);
        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha", $hash);
        $sql->bindValue(":telefone", $telefone);
        $sql->bindValue(":tipo_usuario", $tipo_usuario);
        $sql->bindValue(":matricula", empty($matricula) ? null : $matricula);
        $sql->bindValue(":foto_perfil", $foto);
        $sql->execute();
        
        return $conn->lastInsertId();
    }

    public function atualizar($id, $nome, $email, $telefone = null, $tipo_usuario = 'aluno', $matricula = null, $senha = null, $foto = null) {
        $conn = BD::getConnection();
        
        $usuario = self::getById($id);
        
        if (!$usuario) {
            throw new Exception("Usuário não encontrado");
        }
        
        if (self::emailExiste($email, $id)) {
            throw new Exception("Email já cadastrado para outro usuário");
        }
        
        if ($matricula && self::matriculaExiste($matricula, $id)) {
            throw new Exception("Matrícula já cadastrada para outro usuário");
        }
        
        if (is_array($foto) && is_uploaded_file($foto['tmp_name'])) {
            if ($usuario->foto_perfil && file_exists(self::uploadDir . $usuario->foto_perfil)) {
                unlink(self::uploadDir . $usuario->foto_perfil);
            }
            $foto = self::uploadFoto($foto);
        } else {
            $foto = $usuario->foto_perfil;
        }
        
        $campos = "nome = :nome, email = :email, telefone = :telefone, tipo_usuario = :tipo_usuario, matricula = :matricula, foto_perfil = :foto_perfil";
        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':tipo_usuario' => $tipo_usuario,
            ':matricula' => $matricula,
            ':foto_perfil' => $foto,
            ':id' => $id
        ];
        
        if ($senha !== null && $senha !== '') {
            $campos .= ", senha = :senha";
            $params[':senha'] = self::hashSenha($senha);
        }
        
        $sql = $conn->prepare("UPDATE usuarios SET $campos WHERE id = :id");
        
        foreach ($params as $param => $value) {
            $sql->bindValue($param, $value);
        }
        
        return $sql->execute();
    }

    public function deletar($id) {
        $conn = BD::getConnection();
        
        $usuario = self::getById($id);
        
        if ($usuario && $usuario->foto_perfil && file_exists(self::uploadDir . $usuario->foto_perfil)) {
            unlink(self::uploadDir . $usuario->foto_perfil);
        }
        
        $sql = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $sql->bindValue(":id", $id);
        
        return $sql->execute();
    }

    public function alterarStatus($id, $ativo) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("UPDATE usuarios SET ativo = :ativo WHERE id = :id");
        $sql->bindValue(":ativo", $ativo ? 1 : 0);
        $sql->bindValue(":id", $id);
        
        return $sql->execute();
    }

    private static function emailExiste($email, $excludeId = null) {
        $conn = BD::getConnection();
        
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = [':email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    private static function matriculaExiste($matricula, $excludeId = null) {
        $conn = BD::getConnection();
        
        $sql = "SELECT COUNT(*) FROM usuarios WHERE matricula = :matricula AND matricula IS NOT NULL";
        $params = [':matricula' => $matricula];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    private static function hashSenha($senha) {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    public static function verificarSenha($senha, $hash) {
        return password_verify($senha, $hash);
    }

    private static function uploadFoto($arquivo) {
        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erro no upload do arquivo");
        }
        
        if (!in_array($arquivo['type'], self::tiposPermitidos)) {
            throw new Exception("Tipo de arquivo não permitido. Use apenas JPEG ou PNG.");
        }
        
        if ($arquivo['size'] > self::maxSize) {
            throw new Exception("Arquivo muito grande. Tamanho máximo: 5MB");
        }
        
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoDestino = self::uploadDir . $nomeArquivo;
        
        if (!is_dir(self::uploadDir)) {
            mkdir(self::uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            return $nomeArquivo;
        } else {
            throw new Exception("Erro ao salvar o arquivo");
        }
    }

    public static function autenticar($email, $senha) {
        $conn = BD::getConnection();
        
        $sql = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND ativo = 1");
        $sql->bindValue(":email", $email);
        $sql->execute();
        
        $usuario = $sql->fetch(PDO::FETCH_OBJ);
        
        if ($usuario && self::verificarSenha($senha, $usuario->senha)) {
            // Registra o último login
            self::registrarLogin($usuario->id);
            return $usuario;
        }
        
        return false;
    }

    public static function buscar($busca = '', $tipo = '') {
        $conn = BD::getConnection();
        
        $sql = "SELECT id, nome, email, telefone, tipo_usuario, matricula, ativo, data_cadastro FROM usuarios WHERE 1=1";
        $params = [];
        
        if (!empty($busca)) {
            $sql .= " AND (nome LIKE :busca OR email LIKE :busca OR matricula LIKE :busca)";
            $params[':busca'] = "%$busca%";
        }
        
        if (!empty($tipo)) {
            $sql .= " AND tipo_usuario = :tipo";
            $params[':tipo'] = $tipo;
        }
        
        $sql .= " ORDER BY nome";
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function contarPorTipo($tipo = '') {
        $conn = BD::getConnection();
        
        $sql = "SELECT COUNT(*) FROM usuarios WHERE ativo = 1";
        $params = [];
        
        if (!empty($tipo)) {
            $sql .= " AND tipo_usuario = :tipo";
            $params[':tipo'] = $tipo;
        }
        
        $stmt = $conn->prepare($sql);
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
}
