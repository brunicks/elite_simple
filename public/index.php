<?php
// public/index.php - Front Controller do MVC

// Ativar exibição de erros durante desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

session_start();

// Definir constantes de caminho
define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');
define('PUBLIC_PATH', __DIR__);

// Incluir configuração
if (!file_exists(ROOT . '/config/config.php')) {
    die('Arquivo de configuração não encontrado: ' . ROOT . '/config/config.php');
}

require_once ROOT . '/config/config.php';

// Incluir classes do core
$coreFiles = [
    APP . '/Core/App.php',
    APP . '/Core/Controller.php',
    APP . '/Core/Model.php'
];

foreach ($coreFiles as $file) {
    if (!file_exists($file)) {
        die('Arquivo core não encontrado: ' . $file);
    }
    require_once $file;
}

// Tentar iniciar aplicação
try {
    $app = new App();
} catch (Exception $e) {
    // Em caso de erro, mostrar mensagem detalhada se DEBUG estiver ativo
    if (defined('DEBUG') && DEBUG) {
        echo "<h1>Erro na Aplicação</h1>";
        echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "<h1>Erro interno do servidor</h1>";
        echo "<p>Ocorreu um erro. Tente novamente mais tarde.</p>";
    }
}
?>
