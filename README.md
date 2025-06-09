# 🚗 Elite Motors - Sistema de Concessionária

Sistema profissional de gestão de concessionária desenvolvido em PHP com arquitetura MVC, oferecendo experiência completa para clientes e administradores.

## ⚡ Como Rodar a Ferramenta

### Requisitos do Sistema
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server
- Git (para clonar o repositório)

### Passo a Passo para Instalação

1. **Clone o repositório do GitHub**
```bash
git clone https://github.com/SEU_USUARIO/elite-motors.git
cd elite-motors
```

2. **Configure o banco de dados**
```bash
# 1. Crie um banco de dados MySQL
# 2. Importe o arquivo: docs/database.sql
# 3. Configure as credenciais em: config/config.php
```

3. **Configure as permissões de upload**
```bash
# Garanta que as pastas tenham permissão de escrita:
public/uploads/cars/
public/uploads/users/
public/uploads/temp/
```

4. **Inicie o servidor**
```bash
# Navegue até a pasta public
cd public

# Execute o servidor PHP
php -S localhost:8080

# Acesse no navegador: http://localhost:8080
```

### Configuração Adicional
- **URL Base**: Edite `config/config.php` e ajuste `BASE_URL` conforme seu ambiente
- **Email**: Configure `app/Config/EmailConfig.php` para notificações (opcional)
- **Debug**: Mantenha `DEBUG = true` durante desenvolvimento

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

## 🏗️ Tecnologias Utilizadas

### Arquitetura do Sistema
- **Padrão MVC**: Model-View-Controller para separação de responsabilidades
- **PHP Orientado a Objetos**: Classes e herança para organização do código
- **Roteamento Customizado**: Sistema de URLs amigáveis sem frameworks externos
- **Autoloading**: Carregamento automático de classes
- **Padrão Singleton**: Para conexão com banco de dados
- **Session Management**: Gerenciamento de sessões PHP para autenticação

### Frontend
- **HTML5**: Estrutura semântica moderna
- **CSS3**: Estilização avançada com Flexbox e Grid
- **JavaScript Vanilla**: Interações dinâmicas sem dependências
- **AJAX**: Requisições assíncronas para favoritos e comparações
- **Design Responsivo**: Mobile-first com breakpoints otimizados
- **Font Awesome**: Iconografia profissional

### Backend
- **PHP 7.4+**: Linguagem principal do servidor
- **PDO**: Abstração de banco de dados com prepared statements
- **BCrypt**: Criptografia de senhas
- **File Upload**: Sistema de upload otimizado para imagens
- **Error Handling**: Tratamento de erros personalizado

### Banco de Dados
O sistema utiliza **MySQL 5.7+** com a seguinte estrutura:

#### Tabelas Principais:
- **carros**: Armazena informações completas dos veículos
  - Dados básicos: marca, modelo, ano, preço, quilometragem
  - Especificações técnicas: motor, potência, torque, consumo
  - Status: campo 'ativo' para soft delete
  - Imagens: campo para armazenar nome do arquivo

- **usuarios**: Sistema de autenticação
  - Informações pessoais: nome, email, telefone
  - Credenciais: senha criptografada com bcrypt
  - Níveis de acesso: tipo ('admin' ou 'user')
  - Timestamps: created_at e updated_at

- **favoritos**: Relacionamento muitos-para-muitos
  - usuario_id e carro_id como chaves estrangeiras
  - Constraint UNIQUE para evitar duplicatas
  - Timestamp de quando foi favoritado

- **recently_viewed**: Histórico de visualizações
  - Rastreamento automático de carros visitados
  - Limpeza automática (máximo 10 por usuário)
  - Ordenação por data de visualização

- **financing_simulations**: Simulações de financiamento
  - Valores calculados e salvos para histórico
  - Parâmetros: valor do carro, entrada, prazo, juros
  - Vinculação opcional com carro específico

#### Relacionamentos:
- Users → Favorites (1:N)
- Cars → Favorites (1:N)
- Users → Recently Viewed (1:N)
- Users → Financing Simulations (1:N)
- Cars → Financing Simulations (1:N)

#### Índices e Performance:
- Índices em chaves estrangeiras
- Índice composto em favoritos (usuario_id, carro_id)
- Índice em campos de busca frequente (marca, modelo)

### Segurança
- **Prepared Statements**: Proteção contra SQL Injection
- **Password Hashing**: BCrypt para senhas
- **Session Security**: Verificação de autenticação
- **Input Sanitization**: Limpeza de dados de entrada
- **File Upload Validation**: Verificação de tipos de arquivo
- **CSRF Protection**: Tokens para formulários críticos

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

## 👥 Divisão de Papéis da Equipe

### Desenvolvimento e Responsabilidades

**[Nome do Responsável]** - *Desenvolvedor Full Stack Principal*
- Arquitetura do sistema MVC
- Implementação do backend em PHP
- Sistema de autenticação e autorização
- Integração com banco de dados MySQL

**[Nome do Responsável]** - *Frontend Developer*
- Interface do usuário e experiência (UI/UX)
- Design responsivo com CSS3 e JavaScript
- Sistema de favoritos AJAX
- Componentes visuais e animações

**[Nome do Responsável]** - *Database Administrator*
- Modelagem e estrutura do banco de dados
- Otimização de consultas e índices
- Sistema de backup e recuperação
- Performance e segurança dos dados

**[Nome do Responsável]** - *Quality Assurance & Documentation*
- Testes funcionais e de usabilidade
- Documentação técnica e manual de usuário
- Validação de requisitos
- Deploy e configuração de ambiente

*Nota: Substitua os nomes conforme sua equipe*

## 📞 Suporte

O sistema Elite Motors é auto-documentado com:
- Interface intuitiva para todas as funcionalidades
- Mensagens de erro descritivas
- Tooltips e textos de ajuda
- Validações em tempo real

## 💰 Custo da Ferramenta

### Análise de Custos de Desenvolvimento

**Desenvolvimento do Sistema (200 horas de trabalho)**
- Desenvolvedor Full Stack Principal: 80h × R$ 80/h = R$ 6.400,00
- Frontend Developer: 60h × R$ 70/h = R$ 4.200,00
- Database Administrator: 40h × R$ 75/h = R$ 3.000,00
- Quality Assurance & Documentation: 20h × R$ 60/h = R$ 1.200,00

**Infraestrutura e Ferramentas**
- Hospedagem e domínio (1 ano): R$ 500,00
- Banco de dados MySQL: R$ 300,00
- Ferramentas de desenvolvimento: R$ 200,00

**Custos Adicionais**
- Testes e validação: R$ 800,00
- Documentação e manuais: R$ 600,00
- Deploy e configuração inicial: R$ 400,00

### **Valor Total do Projeto: R$ 17.600,00**

*Valores baseados no mercado brasileiro (2025) para desenvolvimento de sistemas web personalizados*

---

**Elite Motors** - Sistema completo de concessionária com foco na experiência do usuário e eficiência administrativa.