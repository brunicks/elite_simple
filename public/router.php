<?php
// router.php - Router CORRIGIDO para servidor PHP embutido e ngrok

// Log de debug (remover em produção)
error_log("Router.php - REQUEST_URI: " . $_SERVER['REQUEST_URI']);

// Verificar se é um arquivo estático (CSS, JS, imagens, etc.)
$requestUri = $_SERVER['REQUEST_URI'];
$parsedUrl = parse_url($requestUri);
$path = $parsedUrl['path'];

// Remover barras duplas e normalizar o caminho
$path = preg_replace('#/+#', '/', $path);

// Se o caminho não começa com /assets, mas o REQUEST_URI sim, manter /assets
if (strpos($_SERVER['REQUEST_URI'], '/assets/') !== false && strpos($path, '/assets/') === false) {
    $path = '/assets' . $path;
}

// Caminho do arquivo no sistema
$filePath = __DIR__ . $path;

// Log de debug
error_log("Router.php - Caminho solicitado: " . $path);
error_log("Router.php - Caminho do arquivo: " . $filePath);

// Lista de extensões de arquivos estáticos permitidos
$staticExtensions = [
    'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 
    'woff', 'woff2', 'ttf', 'eot', 'pdf', 'txt', 'map'
];

// Verificar se é um arquivo estático
$extension = pathinfo($path, PATHINFO_EXTENSION);

if (in_array(strtolower($extension), $staticExtensions)) {
    // Verificar se o arquivo existe
    if (file_exists($filePath) && is_readable($filePath)) {
        // Definir Content-Type baseado na extensão
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
            'map' => 'application/json'
        ];
        
        $contentType = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($filePath));
        
        // Cache headers para arquivos estáticos
        header('Cache-Control: public, max-age=3600');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        
        readfile($filePath);
        exit; // IMPORTANTE: usar exit em vez de return
    } else {
        // Arquivo não encontrado
        http_response_code(404);
        error_log("Router.php - Arquivo não encontrado: " . $filePath);
        echo "Arquivo não encontrado: " . htmlspecialchars($path);
        exit; // IMPORTANTE: usar exit em vez de return
    }
}

// Se não é arquivo estático, usar o index.php
require_once __DIR__ . '/index.php';
