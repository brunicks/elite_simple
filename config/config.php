<?php
// config.php - Configuração principal do EliteMotors

/**
 * ========================================
 * CONFIGURAÇÕES ESSENCIAIS
 * ========================================
 */

// Debug mode
define('DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir caminhos do projeto apenas se não estiverem definidos
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}
if (!defined('APP')) {
    define('APP', ROOT . '/app');
}

define('DB_HOST', 'localhost');
define('DB_NAME', 'concessionaria2');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Sistema automático de BASE_URL com detecção HTTPS melhorada
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
           || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
           || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
           || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
           || (strpos($_SERVER['HTTP_HOST'] ?? '', 'ngrok') !== false); // Força HTTPS para ngrok

$protocol = $isHttps ? 'https://' : 'http://';
$path = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/') . '/';
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . $path);

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
