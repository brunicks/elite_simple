# 🚗 Elite Motors - Sistema de Concessionária

Sistema profissional de gestão de concessionária desenvolvido em PHP com arquitetura MVC, oferecendo experiência completa para clientes e administradores.

## ⚡ Início Rápido

### Requisitos
- PHP 7.4+
- MySQL 5.7+
- Servidor web (Apache/Nginx) ou PHP built-in server

### Instalação

1. **Clone ou baixe o projeto**
```bash
# Configure a base URL no arquivo de configuração
config/config.php
```

2. **Configure o banco de dados**
```bash
# Importe o arquivo SQL (se disponível) ou use as configurações em:
config/database.php
```

3. **Inicie o servidor**
```bash
# Para desenvolvimento local
cd public
php -S localhost:8080

# Acesse: http://localhost:8080
```

## 🎯 Funcionalidades Principais

### 👥 Para Clientes
- **Catálogo Completo**: Navegação com filtros avançados por marca, preço, ano, combustível
- **Sistema de Favoritos**: Salve carros de interesse com sincronização em tempo real
- **Comparação de Veículos**: Compare até 3 carros simultaneamente
- **Calculadora de Financiamento**: Simule financiamentos personalizados
- **Visualizações Recentes**: Histórico automático dos carros visitados
- **Dashboard Personalizado**: Central com suas atividades e preferências

### 🔧 Para Administradores
- **Gestão de Estoque**: CRUD completo de veículos com upload de imagens
- **Painel de Controle**: Visão geral do sistema com estatísticas em tempo real
- **Gestão de Usuários**: Controle de acesso e promoção de permissões
- **Filtros Administrativos**: Visualizar carros ativos, inativos ou todos
- **Soft Delete**: Sistema de desativação reversível de veículos

## 🏗️ Arquitetura

### Estrutura MVC
```
app/
├── Controllers/    # Lógica de controle
├── Models/        # Modelos de dados
├── Core/          # Classes base do sistema
└── Services/      # Serviços auxiliares

views/
├── layouts/       # Templates base
├── home/         # Página inicial
├── cars/         # Catálogo e detalhes
└── dashboard/    # Painéis de usuário/admin

public/           # Ponto de entrada
config/          # Configurações do sistema
docs/            # Documentação técnica
```

### Banco de Dados
- **carros**: Estoque de veículos com dados técnicos completos
- **usuarios**: Sistema de autenticação com níveis de acesso
- **favoritos**: Relacionamento usuário-veículo para favoritos
- **recently_viewed**: Histórico de visualizações
- **financing_simulations**: Simulações de financiamento salvas

## 🚀 Principais Recursos

### Sistema de Favoritos AJAX
- Toggle instantâneo sem reload da página
- Contador visual em tempo real
- Proteção contra duplicatas no banco

### Filtros Avançados
- **Colapsáveis**: Interface limpa e organizada
- **Múltiplos Critérios**: Marca, modelo, preço, ano, combustível, cor
- **Persistência**: Filtros mantidos durante navegação
- **Reset Rápido**: Limpar todos os filtros com um clique

### Dashboard Inteligente
- **Usuários**: Favoritos, simulações, carros visitados
- **Admins**: Gestão completa com estatísticas em tempo real
- **Responsivo**: Interface adaptada para mobile

### Paginação Otimizada
- 12 carros por página para performance ideal
- Navegação numérica + primeira/última página
- Contadores informativos de resultados

## 🔐 Sistema de Autenticação

### Níveis de Acesso
- **Visitantes**: Visualizam catálogo, modal de login para favoritos
- **Usuários**: Acesso completo a favoritos, simulações e dashboard
- **Administradores**: Gestão total do sistema + funcionalidades de usuário

### Segurança
- Senhas criptografadas com bcrypt
- Verificação de sessões em todas as áreas protegidas
- Sanitização de inputs contra XSS
- Proteção de rotas baseada em permissões

## 📱 Interface e Experiência

### Design Responsivo
- **Mobile-first**: Otimizado para dispositivos móveis
- **Cards Modernos**: Layout em grid com elementos visuais atrativos
- **Cores Premium**: Paleta dourada (#d4af37) para transmitir luxo
- **Tipografia**: Hierarquia clara e legível

### Interações Fluidas
- **AJAX**: Favoritos e comparações sem reload
- **Feedback Visual**: Animações e estados visuais claros
- **Loading States**: Indicadores de progresso para ações
- **Alertas Informativos**: Notificações de sucesso/erro

## ⚙️ Configuração

### Ambiente de Desenvolvimento
```php
// config/config.php
define('DEBUG', true);
define('BASE_URL', 'http://localhost:8080/');
```

### Email (Opcional)
```php
// Para notificações do sistema
app/Config/EmailConfig.php
```

### Upload de Imagens
```
uploads/cars/    # Diretório para imagens dos veículos
- Formatos: JPG, PNG, GIF
- Redimensionamento automático
- Fallback para placeholder se não houver imagem
```

## 🎛️ Painel Administrativo

### Funcionalidades Principais
- **Vista Geral**: Estatísticas de carros ativos/inativos
- **Busca Avançada**: Localizar veículos por qualquer critério
- **Filtros de Status**: Visualizar apenas ativos, inativos ou todos
- **Ações em Lote**: Operações múltiplas para eficiência
- **Gestão Visual**: Cards informativos com ações diretas

### Gestão de Veículos
- **Cadastro Completo**: Formulários com todos os dados técnicos
- **Upload de Imagens**: Sistema drag-and-drop otimizado
- **Edição Flexível**: Modificar qualquer aspecto do veículo
- **Soft Delete**: Desativar temporariamente sem perder dados

## 📊 Recursos do Dashboard

### Para Usuários
- **Estatísticas Pessoais**: Contadores de favoritos, simulações, visualizações
- **Calculadora Integrada**: Financiamento com base nos carros do estoque
- **Histórico Visual**: Carros visitados com imagens em carousel
- **Ações Rápidas**: Links diretos para funcionalidades principais

### Para Administradores
- **Métricas de Sistema**: Total de carros, usuários, atividade
- **Gestão Unificada**: Todos os controles em uma interface
- **Relatórios Visuais**: Gráficos e indicadores de performance
- **Acesso Rápido**: Botões para todas as funcionalidades administrativas

## 🔄 Fluxo de Navegação

### Usuários Não Logados
```
Home → Catálogo → Modal de Login → Dashboard de Usuário
```

### Usuários Logados
```
Dashboard ↔ Catálogo ↔ Favoritos ↔ Detalhes do Carro
```

### Administradores
```
Dashboard Admin → Gestão de Carros/Usuários → Estatísticas
```

## 📞 Suporte

O sistema Elite Motors é auto-documentado com:
- Interface intuitiva para todas as funcionalidades
- Mensagens de erro descritivas
- Tooltips e textos de ajuda
- Validações em tempo real

---

**Elite Motors** - Sistema completo de concessionária com foco na experiência do usuário e eficiência administrativa.
