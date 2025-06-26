<?php

class BD {
    
    private static $connection = null;
    
    /**
     * Detecta automaticamente as configurações do ambiente
     * @return array
     */
    private static function getConfig() {
        // Detectar se está rodando no Docker
        if (getenv('DOCKER_ENV') || file_exists('/.dockerenv') || gethostname() === 'php') {
            return [
                'host' => 'db',
                'dbname' => 'vaicomigo',
                'username' => 'root',
                'password' => 'root'
            ];
        }
        
        // Configuração para XAMPP local
        return [
            'host' => '127.0.0.1',
            'dbname' => 'vaicomigo',
            'username' => 'root',
            'password' => ''
        ];
    }
    
    /**
     * Função para conectar ao banco de dados
     * @return PDO
     */
    public static function getConnection() {
        if (self::$connection === null) {
            $config = self::getConfig();
            
            // Tentar múltiplas configurações
            $configs = [
                $config, // Configuração detectada
                ['host' => 'localhost', 'dbname' => 'vaicomigo', 'username' => 'root', 'password' => ''], // Fallback XAMPP
                ['host' => 'db', 'dbname' => 'vaicomigo', 'username' => 'root', 'password' => 'root'], // Fallback Docker
            ];
            
            $lastError = null;
            
            foreach ($configs as $cfg) {
                try {
                    self::$connection = new PDO(
                        "mysql:host=" . $cfg['host'] . ";dbname=" . $cfg['dbname'] . ";charset=utf8",
                        $cfg['username'],
                        $cfg['password'],
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_TIMEOUT => 5
                        ]
                    );
                    
                    // Se chegou aqui, a conexão foi bem-sucedida
                    break;
                    
                } catch (PDOException $e) {
                    $lastError = $e;
                    continue;
                }
            }
            
            if (self::$connection === null) {
                die("Erro na conexão: " . $lastError->getMessage() . 
                    "<br><br>Verifique se:<br>" .
                    "- O MySQL está rodando (XAMPP) ou<br>" .
                    "- Os containers Docker estão ativos<br>" .
                    "- O banco 'vaicomigo' foi criado");
            }
        }
        
        return self::$connection;
    }
    
    /**
     * Testa a conexão com o banco
     * @return bool
     */
    public static function testConnection() {
        try {
            $conn = self::getConnection();
            $stmt = $conn->query("SELECT 1");
            return $stmt !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}
