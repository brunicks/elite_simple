# Guia de Desenvolvimento - EliteMotors (Atualizado 2025)

## Visão Geral do Projeto
Sistema profissional de concessionária desenvolvido em PHP puro seguindo padrão MVC, com funcionalidades avançadas de autenticação, favoritos, gestão de veículos, e interface moderna com filtros colapsáveis.

## ⭐ Novas Funcionalidades Implementadas (Maio 2025)

### 1. **Sistema de Filtros Colapsáveis**
- **Localização**: `views/cars/index.php`
- **Funcionalidade**: Filtros modernos com toggle inteligente
- **Características**:
  - Ocultos por padrão com botão "Mostrar Filtros"
  - Auto-abertura quando filtros estão ativos
  - Badge de contagem de filtros ativos
  - Animações suaves com CSS transitions
  - JavaScript para interatividade

```javascript
// Exemplo de implementação JavaScript
document.getElementById('toggleFilters').addEventListener('click', function() {
    const container = document.getElementById('filtersContainer');
    const isVisible = container.style.display !== 'none';
    container.style.display = isVisible ? 'none' : 'block';
    // Lógica de animação e feedback visual
});
```

### 2. **Interface de Usuários Aprimorada**
- **Localização**: `views/user/index.php`
- **Melhorias**: Interface limpa sem IDs técnicos
- **Características**:
  - Remoção da coluna ID da tabela
  - Avatar visual com iniciais dos usuários
  - Status visual com badges coloridos
  - Foco em dados relevantes para gestão

### 3. **Sistema de Roteamento Padronizado**
- **Localização**: `app/Controllers/CarController.php`
- **Nova Feature**: Método `redirectToDashboard()`
- **Funcionalidade**: Redirecionamentos inteligentes baseados em role

```php
// Método helper para redirecionamentos baseados em papel
private function redirectToDashboard() {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        $this->redirect(BASE_URL . 'dashboard');
    } else {
        $this->redirect(BASE_URL . 'dashboard/user');
    }
}
```

## Estrutura do Projeto

```
simple/
├── app/                    # Lógica da aplicação
│   ├── Controllers/        # Controladores MVC
│   ├── Core/              # Classes fundamentais
│   └── Models/            # Modelos de dados
├── config/                # Configurações
├── docs/                  # Documentação
├── public/                # Arquivos públicos (CSS, JS, uploads)
└── views/                 # Templates e layouts
    ├── layouts/           # Layouts reutilizáveis
    └── [modules]/         # Views organizadas por módulo
```

## Padrões de Desenvolvimento

### 1. Arquitetura MVC

#### Controllers
Localização: `app/Controllers/`
- Estendem `Controller` base
- Métodos públicos = rotas acessíveis
- Validação de autenticação via `requireAuth()` e `requireAdmin()`

```php
class ExampleController extends Controller {
    public function index() {
        $this->requireAuth(); // Força login
        $data = ['title' => 'Título'];
        $this->view('module/template', $data);
    }
}
```

#### Models
Localização: `app/Models/`
- Estendem `Model` base
- Contêm lógica de banco de dados
- Métodos bem nomeados e específicos

```php
class ExampleModel extends Model {
    public function getAllItems() {
        ## Padrões de Desenvolvimento Atualizados

### 1. Arquitetura MVC Aprimorada

#### Controllers
Localização: `app/Controllers/`
- Estendem `Controller` base
- **NOVO**: Métodos helper para redirecionamento (`redirectToDashboard()`)
- Validação de autenticação via `requireAuth()` e `requireAdmin()`
- URLs padronizadas e consistentes

```php
class ExampleController extends Controller {
    
    // Helper method para redirecionamentos baseados em role
    private function redirectToDashboard() {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
            $this->redirect(BASE_URL . 'dashboard');
        } else {
            $this->redirect(BASE_URL . 'dashboard/user');
        }
    }
    
    public function index() {
        $this->requireAuth();
        $data = ['title' => 'Título'];
        $this->view('module/template', $data);
    }
    
    public function create() {
        $this->requireAdmin();
        // ... lógica de criação ...
        $this->redirectToDashboard(); // Uso do helper
    }
}
```

#### Models
Localização: `app/Models/`
- Estendem `Model` base
- **ATUALIZADO**: Suporte completo a soft delete
- Métodos otimizados com índices
- Queries eficientes para paginação

```php
class ExampleModel extends Model {
    
    // Método com soft delete integrado
    public function getAllActiveItems($page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        return $this->fetchAll(
            "SELECT * FROM items WHERE ativo = 1 ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
    }
    
    // Contagem para paginação
    public function getActiveItemsCount($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM items WHERE ativo = 1";
        // ... lógica de filtros ...
        return $this->fetch($sql, $params)['total'];
    }
}
```

#### Views com Sistema de Filtros
Localização: `views/`
- **NOVO**: Sistema de filtros colapsáveis
- Layout system unificado obrigatório
- JavaScript modular para interatividade

```php
<?php
$title = 'Título da Página';
include_once ROOT . '/views/layouts/header.php';
?>

<main class="container">
    <!-- NOVO: Sistema de filtros colapsáveis -->
    <div class="filters-section">
        <div class="filters-toggle">
            <button type="button" id="toggleFilters" class="btn btn-outline-primary">
                <i class="fas fa-filter"></i>
                <span id="filterToggleText">Mostrar Filtros</span>
                <?php if (!empty($activeFilters)): ?>
                    <span id="filterCount" class="badge"><?= count($activeFilters) ?></span>
                <?php endif; ?>
                <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
            </button>
        </div>
        
        <div id="filtersContainer" style="display: none;">
            <!-- Formulário de filtros -->
        </div>
    </div>
    
    <!-- Conteúdo da página -->
</main>

<!-- NOVO: JavaScript para filtros colapsáveis -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    const container = document.getElementById('filtersContainer');
    const hasActiveFilters = <?= !empty($activeFilters) ? 'true' : 'false' ?>;
    
    // Auto-abrir se há filtros ativos
    if (hasActiveFilters) {
        container.style.display = 'block';
        updateToggleState(true);
    }
    
    toggleButton.addEventListener('click', function() {
        const isVisible = container.style.display !== 'none';
        container.style.display = isVisible ? 'none' : 'block';
        updateToggleState(!isVisible);
    });
    
    function updateToggleState(isOpen) {
        const text = document.getElementById('filterToggleText');
        const icon = document.getElementById('filterToggleIcon');
        
        text.textContent = isOpen ? 'Ocultar Filtros' : 'Mostrar Filtros';
        icon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        toggleButton.style.borderColor = isOpen ? '#28a745' : '#007bff';
        toggleButton.style.color = isOpen ? '#28a745' : '#007bff';
    }
});
</script>

<?php include_once ROOT . '/views/layouts/footer.php'; ?>
```
- JavaScript `toggleFavorite()` para interações AJAX

### 2. **Sistema de Interface Limpa**
- **Princípio**: Mostrar apenas informações relevantes para o usuário
- **Exemplo**: Tabela de usuários sem coluna ID técnico
- **Foco**: UX profissional em vez de dados técnicos

```php
<!-- ANTES: Interface técnica -->
<th>ID</th>
<th>Nome</th>
<td><?= $user['id'] ?></td>
<td><?= $user['name'] ?></td>

<!-- DEPOIS: Interface user-friendly -->
<th>Nome</th>
<th>Email</th>
<td>
    <div class="d-flex align-items-center">
        <div class="avatar-sm me-2">
            <div class="avatar-title bg-primary text-white rounded-circle">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
        </div>
        <span><?= htmlspecialchars($user['name']) ?></span>
    </div>
</td>
<td><?= htmlspecialchars($user['email']) ?></td>
```

### 3. **Sistema de URLs Padronizadas**
- **Princípio**: Usar helpers em vez de URLs hardcoded
- **Implementação**: Método `redirectToDashboard()` para redirecionamentos baseados em role
- **Benefício**: Manutenibilidade e consistência

```php
// ANTES: URLs hardcoded
$this->redirect('/dashboard');
$this->redirect('/dashboard/admin');

// DEPOIS: Helper inteligente
$this->redirectToDashboard(); // Automaticamente detecta o role e redireciona
```

### 4. **Performance e Otimização**
- **Paginação**: Sistema inteligente que preserva filtros
- **Soft Delete**: Índices otimizados para queries eficientes
- **JavaScript**: Carregamento assíncrono e interactions fluidas

```php
// Paginação eficiente com filtros preservados
public function index() {
    $page = (int)($_GET['page'] ?? 1);
    $filters = $this->getFiltersFromRequest();
    
    $cars = $this->carModel->getCarsPaginated($page, 12, $filters);
    $totalCars = $this->carModel->getCarsCount($filters);
    $totalPages = ceil($totalCars / 12);
    
    $data = [
        'cars' => $cars,
        'current_page' => $page,
        'total_pages' => $totalPages,
        'filters' => $filters,
        'title' => 'Catálogo'
    ];
    
    $this->view('cars/index', $data);
}
```

### 1. Nomenclatura (Atualizada)
- **Controllers**: PascalCase + "Controller" (`CarController`)
- **Models**: PascalCase (`Car`, `Favorite`)
- **Métodos**: camelCase (`getAllCars`, `toggleFavorite`)
- **URLs**: kebab-case (`/car/details`, `/user/profile`)

### 2. Estrutura de Métodos no Controller (Atualizada)
```php
public function methodName() {
    // 1. Validação de acesso
    $this->requireAuth();
    
    // 2. Captura de parâmetros
    $param = $_GET['param'] ?? '';
    $page = (int)($_GET['page'] ?? 1);
    
    // 3. Lógica de negócio com filtros/paginação
    $filters = $this->getFiltersFromRequest();
    $data = $this->model->getPaginatedData($page, 12, $filters);
    
    // 4. Preparação de dados para view (incluindo filtros)
    $viewData = [
        'title' => 'Título',
        'data' => $data,
        'filters' => $filters,
        'current_page' => $page,
        'total_pages' => $totalPages
    ];
    
    // 5. Renderização
    $this->view('module/template', $viewData);
}

// Método helper para redirecionamentos
private function redirectToDashboard() {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
        $this->redirect(BASE_URL . 'dashboard');
    } else {
        $this->redirect(BASE_URL . 'dashboard/user');
    }
}
```

### 3. Tratamento de Erros e Feedback Visual (Aprimorado)
```php
// Em controllers - Feedback específico
if (!$data) {
    $_SESSION['error'] = 'Erro específico com orientação para o usuário';
    $this->redirectToDashboard(); // Usar helper em vez de URL hardcoded
}

// Success feedback com detalhes
if ($success) {
    $_SESSION['success'] = 'Ação realizada com sucesso! ' . $detailMessage;
    $this->redirectToDashboard();
}

// Em views - Visual feedback moderno
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= htmlspecialchars($_SESSION['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
```

## Database Design

### Tabelas Principais:
- `carros`: Veículos da concessionária
- `users`: Usuários do sistema
- `favoritos`: Relacionamento user-car para favoritos

### Soft Delete (Em Implementação):
- Coluna `ativo` (TINYINT) em todas as tabelas
- `1` = ativo, `0` = deletado
- Queries sempre incluir `WHERE ativo = 1`

## Frontend Moderno

### CSS Classes Principais (Atualizadas):
- `.container`: Container principal responsivo
- `.btn`, `.btn-primary`, `.btn-success`, `.btn-outline-primary`: Botões modernos
- `.alert`, `.alert-danger`, `.alert-success`: Alertas com ícones
- `.card`, `.car-card`: Cards de conteúdo com sombras
- `.filters-section`, `.filters-toggle`: **NOVO** - Sistema de filtros colapsáveis
- `.badge`: **NOVO** - Contadores visuais para filtros ativos

### JavaScript Moderno (Atualizado):
- Arquivo principal: `public/js/script.js`
- **NOVO**: Módulo de filtros colapsáveis
- **NOVO**: Animações CSS com JavaScript
- Variável global: `window.BASE_URL`
- Padrão AJAX para favoritos e ações dinâmicas

```javascript
// Exemplo: Sistema de filtros colapsáveis
class FilterToggle {
    constructor() {
        this.initializeFilters();
    }
    
    initializeFilters() {
        const toggleButton = document.getElementById('toggleFilters');
        const container = document.getElementById('filtersContainer');
        const hasActiveFilters = document.getElementById('filterCount') !== null;
        
        // Auto-abrir se há filtros ativos
        if (hasActiveFilters) {
            this.showFilters(container, toggleButton);
        }
        
        toggleButton.addEventListener('click', () => {
            this.toggleFilters(container, toggleButton);
        });
    }
    
    toggleFilters(container, button) {
        const isVisible = container.style.display !== 'none';
        if (isVisible) {
            this.hideFilters(container, button);
        } else {
            this.showFilters(container, button);
        }
    }
    
    showFilters(container, button) {
        container.style.display = 'block';
        this.updateButtonState(button, true);
    }
    
    hideFilters(container, button) {
        container.style.display = 'none';
        this.updateButtonState(button, false);
    }
    
    updateButtonState(button, isOpen) {
        const text = button.querySelector('#filterToggleText');
        const icon = button.querySelector('#filterToggleIcon');
        
        text.textContent = isOpen ? 'Ocultar Filtros' : 'Mostrar Filtros';
        icon.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        button.style.borderColor = isOpen ? '#28a745' : '#007bff';
        button.style.color = isOpen ? '#28a745' : '#007bff';
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    new FilterToggle();
});
```

## Comandos de Desenvolvimento Atualizados

### Estrutura de URLs (Finalizada):
```
Públicas:
/                     → HomeController::index()     (Showcase + marketing)
/auth                → AuthController::index()      (Login/register)

Autenticadas:
/dashboard           → DashboardController::index() (Área personalizada)
/favorite            → FavoriteController::index()  (Lista de favoritos)
/car                 → CarController::index()       (Catálogo paginado com filtros)
/car/details/{id}    → CarController::details($id)  (Detalhes do veículo)

Administrativas:
/car/add             → CarController::add()         (Formulário adicionar)
/car/create          → CarController::create()      (Processar criação) 
/car/edit/{id}       → CarController::edit($id)     (Formulário editar)
/car/update          → CarController::update()      (Processar atualização)
/car/delete/{id}     → CarController::delete($id)   (Soft delete)
/user                → UserController::index()      (Gestão usuários)
```

### Upload de Arquivos (Melhorado):
- Diretório: `public/uploads/`
- Validação rigorosa de tipo de arquivo
- Nomes únicos gerados automaticamente
- **NOVO**: Integração com soft delete
- **NOVO**: Cleanup automático de arquivos órfãos

### Performance e Otimização:
- **Paginação**: 12 itens por página para otimizar carregamento
- **Índices**: Otimizados para soft delete e filtros
- **Cache**: Headers apropriados para recursos estáticos
- **JavaScript**: Carregamento assíncrono de módulos

## Checklist para Novas Features (Atualizado)

### ✅ Antes de Implementar:
1. [ ] Controller criado/atualizado com helpers `redirectToDashboard()`
2. [ ] Model necessário existe com suporte a soft delete
3. [ ] View usa novo sistema de layout obrigatoriamente
4. [ ] **NOVO**: Filtros colapsáveis implementados se necessário
5. [ ] **NOVO**: Interface limpa sem dados técnicos desnecessários
6. [ ] Validação de autenticação apropriada
7. [ ] Tratamento de erros com feedback visual
8. [ ] URLs documentadas e padronizadas

### ✅ Após Implementar:
1. [ ] Testado com usuários logados/não logados
2. [ ] Testado com admin/usuário comum
3. [ ] **NOVO**: Filtros colapsáveis funcionando corretamente
4. [ ] **NOVO**: Animações e transições suaves
5. [ ] **NOVO**: Redirecionamentos usando helpers apropriados
6. [ ] Responsividade verificada em dispositivos mobile
7. [ ] Performance verificada com paginação
8. [ ] Documentação atualizada

## Problemas Conhecidos e Soluções (Atualizadas)

### 1. Layout Inconsistente
**Solução**: SEMPRE usar `header.php` + `footer.php`, NUNCA `main.php` (depreciado)

### 2. Filtros não Funcionam ou Interface Confusa
**Verificar**: 
- Sistema de filtros colapsáveis implementado?
- JavaScript carregado corretamente?
- Auto-abertura configurada para filtros ativos?
- Feedback visual funcionando?

### 3. Redirecionamentos Inconsistentes
**Solução**: Usar `$this->redirectToDashboard()` em vez de URLs hardcoded

### 4. Interface Técnica Demais
**Solução**: Remover IDs e dados técnicos da interface do usuário, focar em UX

### 5. Performance Lenta
**Verificar**:
- Paginação implementada? (12 itens por página)
- Índices criados para soft delete?
- Queries otimizadas com LIMIT/OFFSET?

## Próximas Melhorias Sugeridas (Atualizadas)

### 1. **Sistema de Cache** 🚀 (Alta Prioridade)
- **Redis/Memcached**: Para queries frequentes
- **Browser Cache**: Headers apropriados para estáticos
- **Query Cache**: Resultados de filtros comuns

### 2. **API REST** 📱 (Média Prioridade)
- **Endpoints RESTful**: Para futuras aplicações mobile
- **JSON Responses**: Estruturadas e padronizadas
- **Authentication**: JWT para API access

### 3. **Testes Automatizados** 🧪 (Alta Prioridade)
- **PHPUnit**: Testes unitários para models
- **Integration Tests**: Para controllers
- **Frontend Tests**: Para filtros colapsáveis

### 4. **Analytics e Relatórios** 📊 (Baixa Prioridade)
- **Dashboard Analytics**: Métricas de uso
- **Relatórios Admin**: Estatísticas avançadas
- **User Behavior**: Tracking de favoritos

### 5. **Deploy e DevOps** 🚀 (Média Prioridade)
- **CI/CD Pipeline**: Automação de deploy
- **Environment Config**: Staging/Production
- **Monitoring**: Logs e alertas

---

**💡 DICA**: O sistema está agora em um estado profissional e robusto. Foque em testes automatizados e otimização de performance antes de adicionar novas features.
