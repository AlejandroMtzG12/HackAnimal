<?php
class Database {
    private $host = 'mysql.hostinger.com'; // Database host
    private $db = 'u156437425_hackAnimal'; // Database name
    private $user = 'u156437425_hackAnimal'; // Database username
    private $pass = 'hackAnimal1&%#ui*^###'; // Database password, leave empty for root user without password
    private $pdo;

    public function __construct() {
        try {
            // DSN (Data Source Name) string to connect to the database
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";

            // Set PDO options for error handling and fetching
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch as associative array
                PDO::ATTR_PERSISTENT         => true,                     // Use persistent connection
            ];

            // Create a PDO instance
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);

            // Optionally, test the connection with a simple query (this ensures everything works)
            $this->pdo->query("SELECT 1");
        } catch (PDOException $e) {
            // If an error occurs, show an error message and stop script execution
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Getter for the PDO connection
    public function getConnection() {
        return $this->pdo;
    }
}

// Usage example
$db = new Database();
