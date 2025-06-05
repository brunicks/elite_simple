# Models - Organização por Categoria

Esta pasta contém todos os modelos de dados do sistema EliteMotors, organizados por categoria.

## Estrutura Organizada:

### 📁 **Auth/** - Autenticação e Usuários
- `User.php` - Modelo principal de usuários, autenticação e perfis

### 📁 **Vehicle/** - Veículos e Relacionados
- `Car.php` - Modelo principal de carros, CRUD e filtros avançados

### 📁 **User/** - Funcionalidades do Usuário
- `Favorite.php` - Sistema de favoritos
- `RecentlyViewed.php` - Histórico de visualizações
- `FinancingSimulation.php` - Simulador de financiamento

### 📁 **Core/** - Modelos Base e Utilitários
- `BaseModel.php` - Classe base com métodos genéricos CRUD

## Como Usar:

### Carregando Models nos Controllers:
```php
// Uso normal (automático via mapeamento)
$this->userModel = $this->model('User');           // Auth/User.php
$this->carModel = $this->model('Car');             // Vehicle/Car.php
$this->favoriteModel = $this->model('Favorite');   // User/Favorite.php
```

### Criando Novos Models:
1. Escolha a categoria apropriada
2. Estenda BaseModel para métodos genéricos
3. Adicione ao mapeamento em `Core/Controller.php`

### Exemplo de Novo Model:
```php
<?php
require_once APP . '/Models/Core/BaseModel.php';

class MinhaClasse extends BaseModel {
    protected $table = 'minha_tabela';
    
    // Métodos específicos aqui
}
?>
```

## Benefícios desta Organização:
- ✅ **Fácil localização** de models por funcionalidade
- ✅ **Manutenibilidade** melhorada
- ✅ **Escalabilidade** para futuras funcionalidades
- ✅ **Separação clara** de responsabilidades
- ✅ **Reutilização** via BaseModel

## Compatibilidade:
O sistema mantém compatibilidade com códigos antigos através do mapeamento automático no Controller base.
