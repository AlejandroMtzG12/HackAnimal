<?php
class Database {
    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;
    private $ssl;
    private $pdo;

    public function __construct() {
        // Cargar variables de entorno
        $this->host = getenv('DB_HOST');
        $this->port = getenv('DB_PORT') ?: 3306;
        $this->db   = getenv('DB_NAME');
        $this->user = getenv('DB_USER');
        $this->pass = getenv('DB_PASS');
        $this->ssl  = getenv('DB_SSL');

        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT         => true, // conexión persistente
        ];

        // SSL si está habilitado
        if ($this->ssl === "true") {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
            // Opcional: ruta a certificados
            // $options[PDO::MYSQL_ATTR_SSL_CA] = "/ruta/ca-cert.pem";
        }

        $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
    }

    public function getConnection() {
        return $this->pdo;
    }
}
