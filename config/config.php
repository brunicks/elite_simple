<?php
// config.php - Configuração de conexão com banco de dados

// Configurações de debug
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'concessionaria');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuração de charset
define('DB_CHARSET', 'utf8mb4');

// URLs base
define('BASE_URL', 'http://localhost/simple/public/');

try {
    // Criando conexão PDO
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Incluir funções auxiliares (se ainda necessário para compatibilidade)
if (file_exists(__DIR__ . '/../includes/functions.php')) {
    require_once __DIR__ . '/../includes/functions.php';
}
?>
