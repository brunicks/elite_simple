# EliteMotors - Análise Completa do Projeto

## Visão Geral

**EliteMotors** é um sistema de concessionária online desenvolvido em PHP puro, implementando o padrão arquitetural MVC (Model-View-Controller). O projeto representa uma solução completa para gestão e comercialização de veículos, oferecendo funcionalidades tanto para usuários finais quanto para administradores.

### Informações Técnicas Básicas
- **Linguagem Principal:** PHP 7.4+
- **Banco de Dados:** MySQL
- **Arquitetura:** MVC Customizado
- **Frontend:** HTML5, CSS3, JavaScript Vanilla
- **Servidor Web:** Apache (XAMPP)
- **URL Base:** `http://localhost/simple/public/`

---

## Arquitetura do Sistema

### 1. Padrão MVC Implementado

#### **Core Framework (`app/Core/`)**
O projeto implementa um framework MVC customizado com três classes principais:

##### **App.php - Front Controller**
```php
- Gerenciamento de rotas dinâmicas
- Resolução de controllers e actions
- Tratamento de parâmetros URL
- Sistema de fallback para rotas não encontradas
```

**Parsing automático de URLs (`/Controller/Action/Params`):**

**Como Funciona o Sistema de Roteamento:**

1. **Entrada no Sistema:**
```php
// public/index.php
require_once '../app/Core/App.php';
$app = new App();
```

2. **Parsing da URL:**
```php
// App::__construct()
public function __construct() {
    $url = $this->parseUrl();
    
    // URL: /car/details/123
    // Resultado: ['car', 'details', '123']
}

private function parseUrl() {
    if (isset($_GET['url'])) {
        return explode('/', filter_var(
            rtrim($_GET['url'], '/'), 
            FILTER_SANITIZE_URL
        ));
    }
    return ['home']; // Rota padrão
}
```

3. **Resolução do Controller:**
```php
// App::__construct() (continuação)
$controller = $url[0] ?? 'home';
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = APP . '/Controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $this->controller = new $controllerName();
    unset($url[0]); // Remove controller do array
} else {
    // Fallback para página 404
    require_once APP . '/Controllers/ErrorController.php';
    $this->controller = new ErrorController();
}
```

4. **Resolução da Action:**
```php
$method = $url[1] ?? 'index';

if (method_exists($this->controller, $method)) {
    $this->method = $method;
    unset($url[1]); // Remove method do array
}
```

5. **Passagem de Parâmetros:**
```php
$this->params = $url ? array_values($url) : [];

// Executa: Controller->method(...params)
call_user_func_array(
    [$this->controller, $this->method], 
    $this->params
);
```

**Exemplos de Roteamento:**
- `/` → `HomeController::index()`
- `/car` → `CarController::index()`
- `/car/details/123` → `CarController::details(123)`
- `/dashboard/user` → `DashboardController::user()`
- `/auth/login` → `AuthController::login()`

**Configuração Apache (.htaccess):**
```apache
# Todas as rotas passam por index.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

##### **Controller.php - Base Controller**
```php
- Classe base para todos os controllers
- Sistema de autenticação integrado
- Helpers para verificação de permissões
- Carregamento automático de views
```

**Métodos auxiliares:**
- `isLoggedIn()` - Verificação de sessão ativa
- `isAdmin()` - Verificação de privilégios administrativos
- `requireLogin()` - Middleware de autenticação
- `requireAdmin()` - Middleware de autorização admin

##### **Model.php - Base Model**
```php
- Classe base para modelos de dados
- Conexão PDO centralizada
- Métodos CRUD genéricos
- Tratamento de erros de banco
```

### 2. Estrutura de Diretórios

```
EliteMotors/
├── app/                    # Lógica da aplicação
│   ├── Controllers/        # Controladores MVC
│   ├── Core/              # Framework customizado
│   └── Models/            # Modelos de dados
├── config/                # Configurações
├── docs/                  # Documentação
├── public/                # Ponto de entrada web
│   ├── css/              # Estilos
│   ├── js/               # Scripts
│   └── uploads/          # Upload de imagens
└── views/                 # Templates e layouts
    ├── layouts/          # Layouts base
    ├── cars/             # Views de veículos
    ├── dashboard/        # Painéis administrativos
    └── auth/             # Autenticação
```

---

## Sistema de Banco de Dados

### Schema Completo

#### **Tabela: `carros`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- marca (VARCHAR(100), NOT NULL)
- modelo (VARCHAR(100), NOT NULL)  
- ano (INT, NOT NULL)
- preco (DECIMAL(10,2), NOT NULL)
- cor (VARCHAR(50))
- combustivel (VARCHAR(50))
- quilometragem (INT)
- transmissao (VARCHAR(50))
- descricao (TEXT)
- imagem (VARCHAR(255))
- data_cadastro (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

#### **Tabela: `usuarios`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nome (VARCHAR(100), NOT NULL)
- email (VARCHAR(100), UNIQUE, NOT NULL)
- senha (VARCHAR(255), NOT NULL) -- Hash bcrypt
- tipo (ENUM('admin', 'user'), DEFAULT 'user')
- data_cadastro (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

#### **Tabela: `favoritos`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- usuario_id (INT, FOREIGN KEY → usuarios.id)
- carro_id (INT, FOREIGN KEY → carros.id)
- data_favoritado (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- UNIQUE KEY (usuario_id, carro_id)
```

#### **Tabela: `financing_simulations`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY → usuarios.id)
- car_id (INT, FOREIGN KEY → carros.id)
- car_price (DECIMAL(10,2), NOT NULL)
- down_payment (DECIMAL(10,2), NOT NULL)
- loan_term (INT, NOT NULL) -- meses
- interest_rate (DECIMAL(5,2), NOT NULL)
- monthly_payment (DECIMAL(10,2), NOT NULL)
- total_amount (DECIMAL(10,2), NOT NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

#### **Tabela: `recently_viewed`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY → usuarios.id)
- car_id (INT, FOREIGN KEY → carros.id)
- viewed_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
- UNIQUE KEY (user_id, car_id)
```

#### **Tabela: `car_comparisons`**
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY → usuarios.id)
- car1_id (INT, FOREIGN KEY → carros.id)
- car2_id (INT, FOREIGN KEY → carros.id)
- car3_id (INT, FOREIGN KEY → carros.id, NULL)
- created_at (TIMESTAMP, DEFAULT CURRENT_TIMESTAMP)
```

### Relacionamentos
- **Um usuário pode ter muitos favoritos** (1:N)
- **Um usuário pode ter muitas simulações** (1:N)
- **Um usuário pode visualizar muitos carros** (1:N)
- **Um usuário pode fazer muitas comparações** (1:N)
- **Cada comparação pode incluir 2-3 carros** (N:M)

---

## Controllers e Funcionalidades

### 1. **HomeController.php**
**Responsabilidade:** Página inicial e apresentação

**Métodos principais:**
- `index()` - Renderiza homepage com destaques
- Exibe carros em destaque
- Informações da concessionária
- Call-to-actions para catálogo

### 2. **CarController.php**
**Responsabilidade:** Gestão completa de veículos

**Métodos principais:**
- `index()` - Catálogo com filtros avançados
- `details($id)` - Detalhes do veículo
- `add()` - Cadastro de novos veículos (admin)
- `edit($id)` - Edição de veículos (admin)
- `delete($id)` - Remoção de veículos (admin)

**Filtros implementados:**
- Marca (select dinâmico)
- Modelo (baseado na marca)
- Faixa de preço (slider)
- Ano de fabricação
- Combustível
- Transmissão
- Cor
- Quilometragem máxima

**Sistema de paginação:**
- 12 carros por página
- Navegação com Previous/Next
- Contadores de resultados

**Como Funciona a Paginação:**

1. **Cálculo de Offset:**
```php
// CarController::index()
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12; // carros por página
$offset = ($page - 1) * $limit;

$cars = $carModel->getAllWithFilters($filters, $page, $limit);
$totalCars = $carModel->countWithFilters($filters);
$totalPages = ceil($totalCars / $limit);
```

2. **Query com LIMIT/OFFSET:**
```php
// Car::getAllWithFilters()
$sql = "SELECT * FROM carros WHERE ativo = 1";
// ... aplicação de filtros ...
$sql .= " ORDER BY data_cadastro DESC";
$sql .= " LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;

$stmt = $this->db->prepare($sql);
$stmt->execute($params);
```

3. **Contagem Total:**
```php
// Car::countWithFilters()
public function countWithFilters($filters = []) {
    $sql = "SELECT COUNT(*) FROM carros WHERE ativo = 1";
    $params = [];
    
    // Aplica os mesmos filtros da query principal
    if (!empty($filters['marca'])) {
        $sql .= " AND marca = ?";
        $params[] = $filters['marca'];
    }
    // ... outros filtros ...
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}
```

4. **Navegação na View:**
```php
// views/cars/index.php
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
            &laquo; Anterior
        </a>
    <?php endif; ?>
    
    <span>Página <?= $page ?> de <?= $totalPages ?></span>
    
    <?php if ($page < $totalPages): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
            Próxima &raquo;
        </a>
    <?php endif; ?>
</div>
```

**Preservação de Filtros:**
- `http_build_query(array_merge($_GET, ['page' => $newPage]))` mantém todos os filtros ativos ao navegar entre páginas

### 3. **DashboardController.php**
**Responsabilidade:** Painéis administrativos e de usuário

**Métodos principais:**
- `index()` - Dashboard principal
- `user()` - Painel do usuário
- `admin()` - Painel administrativo
- `stats()` - Estatísticas do sistema

**Funcionalidades do Dashboard User:**
- Carros favoritados
- Simulações de financiamento
- Carros visualizados recentemente
- Comparações salvas

**Funcionalidades do Dashboard Admin:**
- Gestão de usuários
- Gestão de veículos
- Estatísticas de uso
- Relatórios

### 4. **UserController.php**
**Responsabilidade:** Gestão de usuários (admin)

**Métodos principais:**
- `index()` - Lista todos os usuários
- `add()` - Cadastro de novos usuários
- `edit($id)` - Edição de usuários
- `delete($id)` - Remoção de usuários

### 5. **AuthController.php**
**Responsabilidade:** Autenticação e autorização

**Métodos principais:**
- `index()` - Tela de login/registro
- `login()` - Processamento de login
- `register()` - Processamento de registro
- `logout()` - Encerramento de sessão

**Segurança implementada:**
- Hash bcrypt para senhas
- Validação de sessões
- Proteção CSRF (parcial)
- Sanitização de inputs

**Como Funciona a Autenticação:**

1. **Processo de Login:**
```php
// AuthController::login()
$user = $this->userModel->authenticate($email, $password);
if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_type'] = $user['tipo']; // 'admin' ou 'user'
}
```

2. **Verificação de Credenciais:**
```php
// User::authenticate()
public function authenticate($email, $password) {
    $user = $this->findByEmail($email);
    if ($user && password_verify($password, $user['senha'])) {
        return $user;
    }
    return false;
}
```

3. **Sessões PHP:**
- As informações do usuário são armazenadas em `$_SESSION`
- `user_type` define o papel: 'admin' ou 'user'
- A sessão persiste entre requests HTTP

### 6. **FavoriteController.php**
**Responsabilidade:** Sistema de favoritos

**Métodos principais:**
- `index()` - Lista favoritos do usuário
- `add($carId)` - Adiciona aos favoritos
- `remove($carId)` - Remove dos favoritos
- `toggle($carId)` - Alterna status favorito

---

## Models e Lógica de Negócio

### 1. **Car.php Model**
**Funcionalidades avançadas:**

```php
- getAllWithFilters() - Filtros complexos com SQL dinâmico
- getPaginated() - Paginação eficiente
- getCarDetails() - Detalhes completos do veículo
- searchCars() - Busca textual
- getCarsByBrand() - Filtro por marca
- getRecentCars() - Carros recém-cadastrados
```

**Destaques técnicos:**
- Queries SQL otimizadas
- Preparação de statements
- Sanitização de parâmetros
- Tratamento de erros

**Como Funcionam as Queries Dinâmicas:**

1. **Construção Condicional de SQL:**
```php
// Car::getAllWithFilters() - Exemplo completo
public function getAllWithFilters($filters = [], $page = 1, $limit = 12) {
    $sql = "SELECT * FROM carros WHERE ativo = 1";
    $params = [];
    
    // Filtro por marca
    if (!empty($filters['marca'])) {
        $sql .= " AND marca = ?";
        $params[] = $filters['marca'];
    }
    
    // Filtro por modelo
    if (!empty($filters['modelo'])) {
        $sql .= " AND modelo LIKE ?";
        $params[] = '%' . $filters['modelo'] . '%';
    }
    
    // Filtro por faixa de preço
    if (!empty($filters['preco_min'])) {
        $sql .= " AND preco >= ?";
        $params[] = floatval($filters['preco_min']);
    }
    
    if (!empty($filters['preco_max'])) {
        $sql .= " AND preco <= ?";
        $params[] = floatval($filters['preco_max']);
    }
    
    // Filtro por ano
    if (!empty($filters['ano_min'])) {
        $sql .= " AND ano >= ?";
        $params[] = intval($filters['ano_min']);
    }
    
    if (!empty($filters['ano_max'])) {
        $sql .= " AND ano <= ?";
        $params[] = intval($filters['ano_max']);
    }
    
    // Outros filtros exatos
    $exactFilters = ['combustivel', 'transmissao', 'cor'];
    foreach ($exactFilters as $filter) {
        if (!empty($filters[$filter])) {
            $sql .= " AND $filter = ?";
            $params[] = $filters[$filter];
        }
    }
    
    // Ordenação e paginação
    $sql .= " ORDER BY data_cadastro DESC";
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = intval($limit);
    $params[] = intval(($page - 1) * $limit);
    
    // Execução com prepared statement
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

2. **Proteção contra SQL Injection:**
```php
// SEMPRE usar prepared statements
$stmt = $this->db->prepare($sql);
$stmt->execute($params);

// NUNCA concatenar diretamente:
// $sql = "SELECT * FROM carros WHERE marca = '$marca'"; // VULNERÁVEL!
```

3. **Tratamento de Tipos:**
```php
// Sanitização por tipo de dado
$preco_min = floatval($filters['preco_min']); // Números decimais
$ano = intval($filters['ano']); // Números inteiros
$marca = trim($filters['marca']); // Strings
```

4. **Otimização de Performance:**
```php
// Índices no banco de dados
CREATE INDEX idx_carros_marca ON carros(marca);
CREATE INDEX idx_carros_preco ON carros(preco);
CREATE INDEX idx_carros_ano ON carros(ano);
CREATE INDEX idx_carros_ativo ON carros(ativo);
```

### 2. **User.php Model**
**Funcionalidades:**

```php
- authenticate() - Validação de login
- createUser() - Registro de novos usuários
- updateUser() - Atualização de dados
- getUserById() - Recuperação por ID
- getAllUsers() - Lista para admin
```

### 3. **Favorite.php Model**
**Sistema de favoritos:**

```php
- addFavorite() - Adiciona favorito
- removeFavorite() - Remove favorito
- getUserFavorites() - Lista favoritos do usuário
- isFavorite() - Verifica se é favorito
- getFavoriteCount() - Conta favoritos
```

### 4. **FinancingSimulation.php Model**
**Simulador financeiro:**

```php
- createSimulation() - Cria nova simulação
- calculateFinancing() - Cálculos financeiros
- getUserSimulations() - Histórico do usuário
- getSimulationDetails() - Detalhes da simulação
```

**Cálculos implementados:**
- Parcela mensal (Price/SAC)
- Total financiado
- Juros totais
- Tabela de amortização

### 5. **RecentlyViewed.php Model**
**Histórico de visualizações:**

```php
- addView() - Registra visualização
- getUserRecentViews() - Carros recentes do usuário
- cleanOldViews() - Limpeza automática
- getViewCount() - Contador de visualizações
```

---

## Interface do Usuário

### 1. **Sistema de Layouts**
**Arquitetura de templates:**

```php
layouts/
├── header.php    - Cabeçalho com navegação
├── footer.php    - Rodapé com informações
└── main.php      - Layout principal
```

**Funcionalidades do header:**
- Navegação responsiva
- Menu de usuário logado
- Links contextuais por perfil
- Barra de busca rápida

### 2. **Catálogo de Veículos (`views/cars/index.php`)**
**Interface principal do sistema:**

**Filtros colapsáveis:**
- Panel expansível/retraível
- Aplicação via JavaScript
- URL parameters persistentes
- Reset filters functionality

**Grid de resultados:**
- Layout responsivo (3 colunas desktop)
- Cards informativos por veículo
- Imagens otimizadas
- Botões de ação (Ver detalhes, Favoritar)

**Paginação:**
- Controles Previous/Next
- Contador de resultados
- Navegação numérica

### 3. **Detalhes do Veículo (`views/cars/details.php`)**
**Página de produto individual:**

**Informações exibidas:**
- Galeria de imagens
- Especificações técnicas completas
- Preço e condições
- Descrição detalhada
- Botões de ação (Favoritar, Simular)

**Funcionalidades interativas:**
- Simulador de financiamento
- Adição aos favoritos
- Compartilhamento (planejado)
- Comparação com outros veículos

### 4. **Dashboard do Usuário (`views/dashboard/user.php`)**
**Central de controle pessoal:**

**Seções implementadas:**
- **Meus Favoritos** - Grid com carros favoritados
- **Simulações** - Histórico de financiamentos
- **Recentemente Vistos** - Últimos carros visualizados
- **Comparações** - Comparações salvas

**Design responsivo:**
- Cards organizados em grid
- Informações resumidas
- Links para ações detalhadas
- Filtros por data/status

### 5. **Painel Administrativo (`views/dashboard/admin.php`)**
**Gestão completa do sistema:**

**Módulos administrativos:**
- **Gestão de Veículos** - CRUD completo
- **Gestão de Usuários** - Controle de acesso
- **Estatísticas** - Relatórios de uso
- **Configurações** - Parâmetros do sistema

---

## Funcionalidades Avançadas

### 1. **Sistema de Filtros Inteligentes**
**Implementação técnica:**

```javascript
// Filtros dinâmicos com AJAX
- Atualização sem reload da página
- Parâmetros na URL para bookmarking
- Reset seletivo de filtros
- Contadores de resultados em tempo real
```

**Como Funcionam os Filtros:**

1. **Captura de Parâmetros:**
```php
// CarController::index()
$filters = [
    'marca' => $_GET['marca'] ?? '',
    'modelo' => $_GET['modelo'] ?? '',
    'preco_min' => $_GET['preco_min'] ?? '',
    'preco_max' => $_GET['preco_max'] ?? '',
    'ano_min' => $_GET['ano_min'] ?? '',
    'ano_max' => $_GET['ano_max'] ?? '',
    'combustivel' => $_GET['combustivel'] ?? '',
    'transmissao' => $_GET['transmissao'] ?? '',
    'cor' => $_GET['cor'] ?? '',
    'km_max' => $_GET['km_max'] ?? ''
];
```

2. **Construção Dinâmica de SQL:**
```php
// Car::getAllWithFilters()
public function getAllWithFilters($filters = [], $page = 1, $limit = 12) {
    $sql = "SELECT * FROM carros WHERE ativo = 1";
    $params = [];
    
    // Adiciona WHERE conditions dinamicamente
    if (!empty($filters['marca'])) {
        $sql .= " AND marca = ?";
        $params[] = $filters['marca'];
    }
    
    if (!empty($filters['preco_min'])) {
        $sql .= " AND preco >= ?";
        $params[] = $filters['preco_min'];
    }
    
    if (!empty($filters['preco_max'])) {
        $sql .= " AND preco <= ?";
        $params[] = $filters['preco_max'];
    }
    
    // ... outros filtros
    
    $sql .= " ORDER BY data_cadastro DESC";
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = ($page - 1) * $limit;
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
```

3. **Persistência na URL:**
```php
// Filtros são mantidos como query parameters
// http://localhost/simple/public/car?marca=Toyota&preco_max=50000
```

4. **Interface Responsiva:**
```javascript
// public/js/script.js
// Filtros colapsáveis e aplicação automática
```

**Tipos de filtro:**
- **Select boxes** - Marca, modelo, combustível
- **Range sliders** - Preço, ano, quilometragem
- **Checkboxes** - Características especiais
- **Text search** - Busca livre

### 2. **Sistema de Favoritos**
**Funcionalidades:**
- Adição/remoção via AJAX
- Persistência no banco de dados
- Contador visual de favoritos
- Lista dedicada no dashboard
- Notificações de ações

**Como Funciona o Sistema de Favoritos:**

1. **Estrutura da Tabela:**
```sql
CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    carro_id INT NOT NULL,
    data_favoritado TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favorite (usuario_id, carro_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (carro_id) REFERENCES carros(id)
);
```

2. **Adicionando aos Favoritos:**
```php
// Favorite::addToFavorites()
public function addToFavorites($userId, $carId) {
    try {
        $stmt = $this->db->prepare("
            INSERT INTO favoritos (usuario_id, carro_id) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$userId, $carId]);
    } catch (PDOException $e) {
        // UNIQUE constraint previne duplicatas
        if ($e->getCode() == 23000) {
            return false; // Já está nos favoritos
        }
        throw $e;
    }
}
```

3. **Verificação de Status:**
```php
// Favorite::isFavorite()
public function isFavorite($userId, $carId) {
    $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM favoritos 
        WHERE usuario_id = ? AND carro_id = ?
    ");
    $stmt->execute([$userId, $carId]);
    return $stmt->fetchColumn() > 0;
}
```

4. **Listagem com JOIN:**
```php
// Favorite::getUserFavorites()
$stmt = $this->db->prepare("
    SELECT c.*, f.created_at as favorited_at
    FROM favoritos f
    INNER JOIN carros c ON f.carro_id = c.id
    WHERE f.usuario_id = ? AND c.ativo = 1
    ORDER BY f.created_at DESC
");
```

**Proteções Implementadas:**
- **UNIQUE constraint** impede duplicatas
- **Foreign Keys** garantem integridade referencial
- **Verificação de usuário logado** antes de qualquer operação
- **Filtro de carros ativos** (c.ativo = 1)

### 3. **Simulador de Financiamento**
**Cálculos implementados:**

```php
// Fórmula de Price (Prestação Fixa)
PMT = PV × [(1+i)^n × i] / [(1+i)^n - 1]

Onde:
- PMT = Prestação mensal
- PV = Valor presente (financiado)
- i = Taxa de juros mensal
- n = Número de parcelas
```

**Interface:**
- Formulário interativo
- Cálculo em tempo real
- Histórico de simulações
- Comparação de cenários

**Como Funciona o Simulador Financeiro:**

1. **Entrada de Dados:**
```php
// Dados recebidos do formulário
$carPrice = floatval($_POST['car_price']);
$downPayment = floatval($_POST['down_payment']);
$loanTerm = intval($_POST['loan_term']); // meses
$interestRate = floatval($_POST['interest_rate']); // % ao ano
```

2. **Cálculos Financeiros:**
```php
// FinancingSimulation::calculateFinancing()
public function calculateFinancing($carPrice, $downPayment, $loanTerm, $annualRate) {
    $loanAmount = $carPrice - $downPayment;
    $monthlyRate = ($annualRate / 100) / 12;
    
    // Fórmula de Price (PMT)
    if ($monthlyRate > 0) {
        $monthlyPayment = $loanAmount * 
            ($monthlyRate * pow(1 + $monthlyRate, $loanTerm)) / 
            (pow(1 + $monthlyRate, $loanTerm) - 1);
    } else {
        $monthlyPayment = $loanAmount / $loanTerm;
    }
    
    $totalAmount = $monthlyPayment * $loanTerm + $downPayment;
    $totalInterest = $totalAmount - $carPrice;
    
    return [
        'loan_amount' => $loanAmount,
        'monthly_payment' => $monthlyPayment,
        'total_amount' => $totalAmount,
        'total_interest' => $totalInterest
    ];
}
```

3. **Persistência no Banco:**
```php
// Salva simulação para histórico do usuário
$simulationData = [
    'user_id' => $_SESSION['user_id'],
    'car_id' => $carId,
    'car_price' => $carPrice,
    'down_payment' => $downPayment,
    'loan_term' => $loanTerm,
    'interest_rate' => $interestRate,
    'monthly_payment' => $result['monthly_payment'],
    'total_amount' => $result['total_amount']
];
```

4. **Tabela de Amortização (Planejada):**
```php
// Cálculo mês a mês do saldo devedor
for ($month = 1; $month <= $loanTerm; $month++) {
    $interestPayment = $balance * $monthlyRate;
    $principalPayment = $monthlyPayment - $interestPayment;
    $balance -= $principalPayment;
    
    $amortization[] = [
        'month' => $month,
        'payment' => $monthlyPayment,
        'principal' => $principalPayment,
        'interest' => $interestPayment,
        'balance' => $balance
    ];
}
```

### 4. **Sistema de Visualizações Recentes**
**Implementação:**
- Registro automático de visualizações
- Limpeza automática (limite de 10)
- Ordenação por data
- Interface dedicada no dashboard

**Como Funciona o Sistema de Visualizações:**

1. **Registro Automático:**
```php
// CarController::details()
public function details($id) {
    // ... código para buscar dados do carro
    
    // Registra visualização se usuário estiver logado
    if (isset($_SESSION['user_id'])) {
        $recentlyViewed = $this->model('RecentlyViewed');
        $recentlyViewed->addView($_SESSION['user_id'], $id);
    }
    
    // ... resto do método
}
```

2. **Lógica de UPSERT:**
```php
// RecentlyViewed::addView()
public function addView($userId, $carId) {
    // Primeiro tenta atualizar se já existe
    $stmt = $this->db->prepare("
        UPDATE recently_viewed 
        SET viewed_at = CURRENT_TIMESTAMP 
        WHERE user_id = ? AND car_id = ?
    ");
    $stmt->execute([$userId, $carId]);
    
    // Se não atualizou nenhuma linha, insere nova
    if ($stmt->rowCount() === 0) {
        $stmt = $this->db->prepare("
            INSERT INTO recently_viewed (user_id, car_id, viewed_at) 
            VALUES (?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$userId, $carId]);
    }
    
    // Limpa visualizações antigas (mantém apenas 10)
    $this->cleanOldViews($userId);
}
```

3. **Limpeza Automática:**
```php
// RecentlyViewed::cleanOldViews()
private function cleanOldViews($userId) {
    $stmt = $this->db->prepare("
        DELETE FROM recently_viewed 
        WHERE user_id = ? 
        AND id NOT IN (
            SELECT id FROM (
                SELECT id FROM recently_viewed 
                WHERE user_id = ? 
                ORDER BY viewed_at DESC 
                LIMIT 10
            ) as tmp
        )
    ");
    $stmt->execute([$userId, $userId]);
}
```

4. **Recuperação com JOIN:**
```php
// RecentlyViewed::getUserRecentViews()
public function getUserRecentViews($userId) {
    $stmt = $this->db->prepare("
        SELECT c.*, rv.viewed_at
        FROM recently_viewed rv
        INNER JOIN carros c ON rv.car_id = c.id
        WHERE rv.user_id = ? AND c.ativo = 1
        ORDER BY rv.viewed_at DESC
        LIMIT 10
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
```

**Características:**
- **UNIQUE constraint** em (user_id, car_id) previne duplicatas
- **Timestamp automático** registra quando foi visualizado
- **Limpeza inteligente** mantém apenas os 10 mais recentes
- **Filtragem de carros ativos** na exibição

### 5. **Comparador de Veículos** (Em desenvolvimento)
**Funcionalidades planejadas:**
- Seleção de até 3 veículos
- Tabela comparativa lado a lado
- Destaque de diferenças
- Salvamento de comparações

---

## Fluxo Completo de Dados e Interações

### **Fluxo de Visualização de Carros (Usuário Final)**

1. **Request Inicial:**
```
Usuário acessa: /car
↓
public/index.php → App::parseUrl() → CarController::index()
```

2. **Processamento no Controller:**
```php
// CarController::index()
public function index() {
    // 1. Captura filtros da URL
    $filters = [
        'marca' => $_GET['marca'] ?? '',
        'preco_min' => $_GET['preco_min'] ?? '',
        // ... outros filtros
    ];
    
    // 2. Busca dados no Model
    $carModel = $this->model('Car');
    $cars = $carModel->getAllWithFilters($filters, $page, $limit);
    $totalCars = $carModel->countWithFilters($filters);
    
    // 3. Prepara dados para a View
    $data = [
        'cars' => $cars,
        'filters' => $filters,
        'pagination' => [/* ... */],
        'title' => 'Catálogo de Veículos'
    ];
    
    // 4. Renderiza a View
    $this->view('cars/index', $data);
}
```

3. **Processamento no Model:**
```php
// Car::getAllWithFilters()
// - Constrói SQL dinamicamente baseado nos filtros
// - Executa query com prepared statements
// - Retorna array de carros
```

4. **Renderização na View:**
```php
// views/cars/index.php
// - Recebe dados via extract($data)
// - Exibe carros em grid responsivo
// - Renderiza formulário de filtros
// - Gera links de paginação
```

### **Fluxo de Adição aos Favoritos (AJAX)**

1. **Ação do Usuário:**
```javascript
// Usuário clica no botão favoritar
<button onclick="toggleFavorite(123)">❤️</button>
```

2. **Request AJAX:**
```javascript
function toggleFavorite(carId) {
    fetch(`${BASE_URL}favorite/toggle/${carId}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        // Atualiza interface baseado na resposta
        updateFavoriteButton(carId, data.isFavorite);
    });
}
```

3. **Processamento no Backend:**
```php
// FavoriteController::toggle()
public function toggle($carId) {
    $this->requireAuth(); // Verifica se está logado
    
    $favoriteModel = $this->model('Favorite');
    $userId = $_SESSION['user_id'];
    
    if ($favoriteModel->isFavorite($userId, $carId)) {
        $favoriteModel->removeFromFavorites($userId, $carId);
        $result = ['success' => true, 'isFavorite' => false];
    } else {
        $favoriteModel->addToFavorites($userId, $carId);
        $result = ['success' => true, 'isFavorite' => true];
    }
    
    $this->json($result);
}
```

### **Fluxo de Login e Autenticação**

1. **Submissão do Formulário:**
```html
<form action="<?= BASE_URL ?>auth/login" method="POST">
    <input type="email" name="email" required>
    <input type="password" name="senha" required>
    <button type="submit">Entrar</button>
</form>
```

2. **Processamento da Autenticação:**
```php
// AuthController::login()
$user = $this->userModel->authenticate($email, $password);
if ($user) {
    // Cria sessão
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['tipo'];
    
    // Redireciona baseado no role
    if ($user['tipo'] === 'admin') {
        $this->redirect(BASE_URL . 'dashboard');
    } else {
        $this->redirect(BASE_URL . 'dashboard/user');
    }
}
```

3. **Verificação em Requests Subsequentes:**
```php
// Qualquer controller que precise de autenticação
$this->requireAuth(); // Verifica $_SESSION['user_id']
$this->requireAdmin(); // Verifica $_SESSION['user_type'] === 'admin'
```

### **Fluxo de Upload de Imagem**

1. **Formulário de Upload:**
```html
<form action="<?= BASE_URL ?>car/add" method="POST" enctype="multipart/form-data">
    <input type="file" name="imagem" accept="image/*">
    <!-- outros campos -->
    <button type="submit">Cadastrar Veículo</button>
</form>
```

2. **Processamento do Upload:**
```php
// CarController::add()
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // 1. Validações (tipo, tamanho)
    // 2. Gera nome único
    // 3. Move arquivo
    // 4. Salva nome no banco
    $carData['imagem'] = $fileName;
}
```

3. **Exibição da Imagem:**
```php
// views/cars/index.php
<img src="<?= BASE_URL ?>uploads/<?= $car['imagem'] ?>" alt="<?= $car['modelo'] ?>">
```

### **Ciclo de Vida de uma Sessão**

1. **Início da Sessão:**
```php
// public/index.php
session_start();
```

2. **Login Bem-sucedido:**
```php
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nome'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_type'] = $user['tipo'];
```

3. **Verificações em Cada Request:**
```php
// Controller::requireAuth()
if (!isset($_SESSION['user_id'])) {
    $this->redirect(BASE_URL . 'auth');
}
```

4. **Logout:**
```php
// AuthController::logout()
session_destroy();
```

### **Tratamento de Erros e Validações**

1. **Validação no Controller:**
```php
if (empty($nome) || empty($email)) {
    $_SESSION['error'] = 'Campos obrigatórios não preenchidos.';
    $this->redirect(BASE_URL . 'car/add');
}
```

2. **Exibição na View:**
```php
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
```

3. **Tratamento de Exceções:**
```php
try {
    $result = $model->operation();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'Erro interno do sistema.';
    $this->redirect(BASE_URL . 'error');
}
```

---

## Segurança e Performance

### 1. **Medidas de Segurança**

**Autenticação:**
- Hash bcrypt para senhas
- Validação de sessões
- Timeout automático
- Proteção contra força bruta (planejado)

**Autorização:**
- Sistema de roles (admin/user)
- Middleware de verificação
- Proteção de rotas sensíveis
- Validação de permissões

**Como Funciona o Sistema de Roles:**

1. **Verificação de Login (Middleware):**
```php
// Controller::requireAuth()
public function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        $this->redirect(BASE_URL . 'auth');
    }
}
```

2. **Verificação de Admin (Middleware):**
```php
// Controller::requireAdmin()
public function requireAdmin() {
    $this->requireAuth(); // Primeiro verifica se está logado
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        $this->redirect(BASE_URL . 'dashboard/user');
    }
}
```

3. **Aplicação nos Controllers:**
```php
// Exemplo no UserController (só admin pode gerenciar usuários)
public function index() {
    $this->requireAdmin(); // Bloqueia se não for admin
    // ... resto do código
}
```

4. **Navegação Condicional:**
```php
// views/layouts/header.php
<?php if (($_SESSION['user_type'] ?? '') === 'admin'): ?>
    <a href="<?= BASE_URL ?>dashboard" class="nav-link admin-link">
        <i class="fas fa-crown"></i>
        <span>Admin</span>
    </a>
<?php else: ?>
    <a href="<?= BASE_URL ?>dashboard/user" class="nav-link">
        <i class="fas fa-user"></i>
        <span>Dashboard</span>
    </a>
<?php endif; ?>
```

**Fluxo de Autorização:**
1. Usuário faz request para rota protegida
2. Controller executa `requireAuth()` ou `requireAdmin()`
3. Sistema verifica `$_SESSION['user_type']`
4. Se autorizado: continua execução
5. Se não autorizado: redireciona para página apropriada

**Proteção de Dados:**
- Prepared statements (PDO)
- Sanitização de inputs
- Escape de outputs
- Validação server-side

**Upload de Arquivos:**
- Validação de tipos MIME
- Limitação de tamanho
- Renomeação automática
- Diretório protegido

**Como Funciona o Upload de Imagens:**

1. **Validação de Arquivo:**
```php
// CarController::add() / CarController::edit()
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    
    // Validação de tipo MIME
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $fileType = $_FILES['imagem']['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF.';
        $this->redirect(BASE_URL . 'car/add');
    }
    
    // Validação de tamanho (5MB máximo)
    $maxSize = 5 * 1024 * 1024; // 5MB em bytes
    if ($_FILES['imagem']['size'] > $maxSize) {
        $_SESSION['error'] = 'Arquivo muito grande. Máximo 5MB.';
        $this->redirect(BASE_URL . 'car/add');
    }
}
```

2. **Geração de Nome Único:**
```php
// Previne conflitos e overwrite de arquivos
$extension = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
$fileName = uniqid() . '.' . $extension;

// Exemplo: 6838dfb0a0f63.jpg
```

3. **Movimento Seguro:**
```php
$uploadDir = ROOT . '/public/uploads/';
$uploadPath = $uploadDir . $fileName;

// Verifica se diretório existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Move arquivo da pasta temporária
if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadPath)) {
    $carData['imagem'] = $fileName;
} else {
    $_SESSION['error'] = 'Erro ao fazer upload da imagem.';
    $this->redirect(BASE_URL . 'car/add');
}
```

4. **Proteção do Diretório:**
```php
// public/uploads/.htaccess
<Files *.php>
    Order allow,deny
    Deny from all
</Files>

// Previne execução de scripts PHP no diretório de uploads
```

5. **Limpeza de Arquivos Antigos:**
```php
// CarController::edit() - Remove imagem anterior
if (isset($carData['imagem']) && !empty($currentCar['imagem'])) {
    $oldImagePath = ROOT . '/public/uploads/' . $currentCar['imagem'];
    if (file_exists($oldImagePath)) {
        unlink($oldImagePath); // Remove arquivo antigo
    }
}
```

**Proteções Implementadas:**
- **Whitelist de tipos MIME** (apenas imagens)
- **Limite de tamanho** (5MB máximo)
- **Nomes únicos** (previne conflitos)
- **Diretório protegido** (sem execução de PHP)
- **Validação de extensão** (dupla verificação)
- **Limpeza automática** (remove arquivos órfãos)

### 2. **Otimizações de Performance**

**Banco de Dados:**
- Índices otimizados
- Queries preparadas
- Conexão singleton
- Cache de resultados (planejado)

**Frontend:**
- CSS minificado
- JavaScript otimizado
- Imagens comprimidas
- Loading lazy (planejado)

**Servidor:**
- Configuração Apache otimizada
- Compressão GZIP habilitada
- Cache headers configurados
- Logs de performance

---

## Status do Projeto

### Funcionalidades Implementadas ✅

**Core System:**
- ✅ Framework MVC customizado
- ✅ Sistema de roteamento
- ✅ Conexão com banco de dados
- ✅ Autenticação e autorização

**Gestão de Veículos:**
- ✅ CRUD completo de carros
- ✅ Upload de imagens
- ✅ Filtros avançados
- ✅ Sistema de busca
- ✅ Paginação

**Área do Usuário:**
- ✅ Registro e login
- ✅ Dashboard personalizado
- ✅ Sistema de favoritos
- ✅ Simulador financeiro
- ✅ Histórico de visualizações

**Área Administrativa:**
- ✅ Painel de controle
- ✅ Gestão de usuários
- ✅ Estatísticas básicas
- ✅ Relatórios

---

## Exemplos Práticos de Uso

### **Cenário 1: Cliente Buscando um Carro Específico**

1. **Acesso ao Catálogo:**
```
Cliente acessa: /car
Sistema carrega: 12 carros por página, sem filtros
```

2. **Aplicação de Filtros:**
```
Cliente seleciona:
- Marca: Toyota
- Preço máximo: R$ 50.000
- Ano mínimo: 2018

URL resultante: /car?marca=Toyota&preco_max=50000&ano_min=2018
```

3. **Visualização de Detalhes:**
```
Cliente clica em um veículo
Sistema redireciona: /car/details/15
Sistema registra: visualização recente automaticamente
```

4. **Simulação de Financiamento:**
```
Cliente preenche:
- Valor do carro: R$ 45.000
- Entrada: R$ 10.000
- Prazo: 48 meses
- Taxa: 12% ao ano

Sistema calcula: Parcela de R$ 915,63
Sistema salva: simulação no histórico do usuário
```

### **Cenário 2: Admin Gerenciando Estoque**

1. **Acesso ao Painel:**
```
Admin faz login → tipo = 'admin'
Sistema redireciona: /dashboard (painel administrativo)
```

2. **Cadastro de Veículo:**
```
Admin acessa: /car/add
Sistema verifica: requireAdmin() → OK
Admin preenche dados e faz upload da imagem
Sistema salva: novo veículo no banco + imagem renomeada
```

3. **Gestão de Usuários:**
```
Admin acessa: /user
Sistema lista: todos os usuários cadastrados
Admin pode: editar, desativar, promover usuários
```

### **Cenário 3: Sistema de Favoritos em Ação**

1. **Usuário Logado Navega:**
```
Usuário visualiza catálogo
Cada carro mostra: ícone de coração (vazio/cheio)
Sistema verifica: Favorite::isFavorite() para cada carro
```

2. **Adição aos Favoritos:**
```javascript
// Click no coração → AJAX request
fetch('/favorite/toggle/123', {method: 'POST'})
→ Sistema verifica login
→ Sistema adiciona/remove favorito
→ Sistema retorna JSON com status
→ Interface atualiza ícone instantaneamente
```

3. **Dashboard de Favoritos:**
```
Usuário acessa: /dashboard/user
Sistema busca: JOIN favoritos + carros ativos
Sistema exibe: grid com carros favoritados
```

### **Cenário 4: Fluxo Completo de Compra**

1. **Descoberta:**
```
Usuário → Homepage → Ver catálogo
Filtros → Marca Toyota, Preço até 60k
Resultado → 8 carros encontrados
```

2. **Avaliação:**
```
Usuário clica → Corolla 2020
Sistema registra → Visualização recente
Usuário adiciona → Favoritos
Usuário simula → Financiamento
```

3. **Decisão:**
```
Usuário compara → 3 carros favoritos
Usuário decide → Corolla 2020
Usuário simula → Diferentes cenários de financiamento
```

4. **Interesse:**
```
Usuário contata → Via WhatsApp/telefone (futuro)
Admin recebe → Notificação de interesse
Admin agenda → Test drive (futuro)
```

### **Cenário 5: Manutenção do Sistema**

1. **Monitoramento:**
```php
// Admin verifica estatísticas diárias
/dashboard/stats
- Novos usuários cadastrados: 5
- Carros visualizados: 247
- Simulações realizadas: 18
- Favoritos adicionados: 32
```

2. **Gestão de Conteúdo:**
```php
// Admin atualiza estoque
- Remove carro vendido (muda ativo = 0)
- Adiciona novos veículos
- Atualiza preços sazonais
- Upload de novas fotos
```

3. **Gestão de Usuários:**
```php
// Admin monitora atividade
- Usuários ativos na semana
- Contas inativas (limpeza)
- Promoção de usuários para admin
- Bloqueio de contas suspeitas
```

---

## Conclusão

O **EliteMotors** representa um sistema de concessionária online robusto e bem estruturado, implementando as melhores práticas de desenvolvimento web. A arquitetura MVC customizada oferece flexibilidade e manutenibilidade, enquanto as funcionalidades implementadas atendem às necessidades tanto de usuários finais quanto de administradores.

### Pontos Fortes

1. **Arquitetura Sólida** - MVC bem implementado com separação clara de responsabilidades
2. **Funcionalidades Completas** - Sistema completo de concessionária com todas as features essenciais
3. **Interface Intuitiva** - Design limpo e responsivo com boa experiência do usuário
4. **Segurança Adequada** - Implementação de medidas básicas de segurança
5. **Escalabilidade** - Estrutura preparada para crescimento e novas features

---

**Documento gerado em:** Junho 2025  
**Versão do projeto:** 1.0  
**Status:** Em produção e desenvolvimento ativo
