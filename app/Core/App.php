<?php
// app/Core/App.php - Classe principal da aplicação

class App {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];    public function __construct() {
        try {
            $url = $this->parseUrl();
            
            // Debug: log da URL apenas se DEBUG estiver ativo
            if (defined('DEBUG') && DEBUG) {
                error_log("App Debug - URL parsed: " . print_r($url, true));
            }
            
            // Roteamento específico com tratamento de erro
            $this->routeRequest($url);
            
            // Executar controller e método
            $this->executeController();
            
        } catch (Exception $e) {
            error_log("App Error: " . $e->getMessage());
            $this->handleError($e);
        }
    }
    
    private function routeRequest($url) {
        $route = $url[0] ?? '';        // Mapear rotas especiais
        $specialRoutes = [
            '' => ['HomeController', 'index'],
            'auth' => ['AuthController', 'index'],
            'dashboard' => ['DashboardController', 'index'],
            'favorite' => ['FavoriteController', 'index']
        ];
          // Rotas com sub-ações
        if ($route === 'auth' && isset($url[1])) {
            $action = $url[1];
            if (in_array($action, ['login', 'register', 'logout', 'forgot_password', 'request_reset', 'reset_password', 'update_password'])) {
                $this->controller = 'AuthController';
                $this->method = $action;
                $this->params = array_slice($url, 2);
                return;
            }        }// Rota /dashboard - com sub-ações (stats, user, etc.)
        if ($route === 'dashboard' && isset($url[1])) {
            $action = $url[1];
            if (in_array($action, ['stats', 'user', 'calculateFinancing', 'saveSimulation', 'deleteSimulation'])) {
                $this->controller = 'DashboardController';
                $this->method = $action;
                $this->params = array_slice($url, 2);
                return;
            }
        }

        // Rota /user - gerenciamento de usuários (apenas admin)
        if ($route === 'user') {
            $this->controller = 'UserController';
            if (isset($url[1])) {
                $action = $url[1];
                if (in_array($action, ['index', 'add', 'create', 'edit', 'update', 'delete', 'reactivate', 'toggleAdmin'])) {
                    $this->method = $action;
                    $this->params = array_slice($url, 2);
                } else {
                    $this->method = 'index';
                    $this->params = array_slice($url, 1);
                }
            } else {
                $this->method = 'index';
                $this->params = [];
            }
            return;
        }

        // Rota /car - catálogo completo com paginação
        if ($route === 'car') {
            $this->controller = 'CarController';
            if (isset($url[1])) {
                $this->method = $url[1];
                $this->params = array_slice($url, 2);
            } else {
                $this->method = 'index'; // Método padrão para /car
                $this->params = [];
            }
            return;
        }          if ($route === 'favorite' && isset($url[1])) {
            $action = $url[1];
            $this->controller = 'FavoriteController';
            $this->method = $action;
            $this->params = array_slice($url, 2);
            return;
        }
        
        // Rota especial
        if (isset($specialRoutes[$route])) {
            $this->controller = $specialRoutes[$route][0];
            $this->method = $specialRoutes[$route][1];
            $this->params = array_slice($url, 1);
            return;
        }
        
        // Roteamento padrão para controllers
        if (!empty($route)) {
            $controllerName = ucfirst($route) . 'Controller';
            $controllerPath = APP . '/Controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                
                if (isset($url[1])) {
                    $this->method = $url[1];
                    $this->params = array_slice($url, 2);
                } else {
                    $this->params = array_slice($url, 1);                }
            }
        }
    }
    
    private function executeController() {
        // Verificar se o arquivo do controller existe
        $controllerPath = APP . '/Controllers/' . $this->controller . '.php';
        
        if (!file_exists($controllerPath)) {
            throw new Exception("Controller file not found: " . $controllerPath);
        }
        
        // Incluir controller
        require_once $controllerPath;
        
        // Verificar se a classe existe
        if (!class_exists($this->controller)) {
            throw new Exception("Controller class not found: " . $this->controller);
        }
        
        // Instanciar controller
        $controllerInstance = new $this->controller;
        
        // Verificar se o método existe
        if (!method_exists($controllerInstance, $this->method)) {
            throw new Exception("Method not found: " . $this->controller . "::" . $this->method);
        }
        
        // Chamar método com parâmetros
        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }
      private function handleError($e) {
        // Log do erro
        error_log("Application Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        
        // Em ambiente de desenvolvimento, mostrar erro detalhado
        if (defined('DEBUG') && DEBUG) {
            echo "<!DOCTYPE html><html><head><title>Erro de Aplicação</title>";
            echo "<style>body{font-family:Arial;margin:20px;} .error{background:#f8f8f8;border:1px solid #ddd;padding:20px;} pre{background:#f0f0f0;padding:10px;overflow:auto;}</style>";
            echo "</head><body>";
            echo "<div class='error'>";
            echo "<h1>🚨 Erro na Aplicação</h1>";
            echo "<p><strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
            echo "<h3>Stack Trace:</h3>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            
            // Mostrar informações de debug úteis
            echo "<h3>Informações de Debug:</h3>";
            echo "<p><strong>URL solicitada:</strong> " . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') . "</p>";
            echo "<p><strong>Controller:</strong> " . htmlspecialchars($this->controller) . "</p>";
            echo "<p><strong>Método:</strong> " . htmlspecialchars($this->method) . "</p>";
            echo "<p><strong>Parâmetros:</strong> " . htmlspecialchars(print_r($this->params, true)) . "</p>";
            echo "</div>";
            echo "</body></html>";
        } else {
            // Em produção, mostrar página de erro simples
            header("HTTP/1.0 500 Internal Server Error");
            echo "<!DOCTYPE html><html><head><title>Erro</title></head><body>";
            echo "<h1>Erro interno do servidor</h1>";
            echo "<p>Ocorreu um erro. Tente novamente mais tarde.</p>";
            echo "</body></html>";
        }
        exit;
    }
      public function parseUrl() {
        // Priorizar $_GET['url'] para compatibilidade com Apache/Nginx
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return $url ? explode('/', $url) : [];
        }
        
        // Para servidor PHP embutido, usar REQUEST_URI
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            $parsedUrl = parse_url($requestUri);
            $path = $parsedUrl['path'] ?? '/';
            
            // Remover trailing slash e normalizar
            $path = rtrim($path, '/');
            if (empty($path) || $path === '/') {
                return [];
            }
            
            // Remover leading slash e dividir em partes
            $path = ltrim($path, '/');
            return $path ? explode('/', $path) : [];
        }
        
        return [];
    }
}
?>
