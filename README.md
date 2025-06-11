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
git clone https://github.com/brunicks/elite_simple.git
cd elite-motors
```

2. **Configure o banco de dados**
```bash
# 1. Crie um banco de dados MySQL
# 2. Importe o arquivo: docs/database.sql
# 3. Configure as credenciais em: config/config.php
```

3. **Inicie o servidor**
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

## 👥 Divisão de Papéis da Equipe

### Desenvolvimento e Responsabilidades

**Lorenzo Bonatto** - *Desenvolvedor Full Stack Principal*
- Arquitetura do sistema MVC
- Implementação do backend em PHP
- Sistema de autenticação e autorização
- Integração com banco de dados MySQL

**Bruno Santos** - *Frontend Developer*
- Interface do usuário e experiência (UI/UX)
- Design responsivo com CSS3 e JavaScript
- Sistema de favoritos AJAX
- Componentes visuais e animações

**Andre Marcondes Ribas** - *Database Administrator*
- Modelagem e estrutura do banco de dados
- Otimização de consultas e índices
- Sistema de backup e recuperação
- Performance e segurança dos dados

**Lorenzo Bonatto de Paula e Andre Marcondes Ribas** - *Quality Assurance & Documentation*
- Testes funcionais e de usabilidade
- Documentação técnica e manual de usuário
- Validação de requisitos
- Deploy e configuração de ambiente

## 📞 Suporte

O sistema Elite Motors é auto-documentado com:
- Interface intuitiva para todas as funcionalidades
- Mensagens de erro descritivas
- Tooltips e textos de ajuda
- Validações em tempo real

## 💰 Custo da Ferramenta
Análise de Custos de Desenvolvimento (SIMULAÇÂO)

### Desenvolvimento do Sistema:
- Desenvolvedor Full Stack: 80h × R$ 80,00 = R$ 6.400,00
- Desenvolvedor Front-End: 60h × R$ 70,00 = R$ 4.200,00
- Administrador de Banco de Dados: 40h × R$ 75,00 = R$ 3.000,00
- Analista de Qualidade: 20h × R$ 60,00 = R$ 1.200,00
- Gerente de Projeto: 20h × R$ 85,00 = R$ 1.700,00
- UX/UI Designer: 30h × R$ 70,00 = R$ 2.100,00
- Testes automatizados: 10h × R$ 70,00 = R$ 700,00
- Ferramentas e Licenças: Custo fixo - R$ 500,00

### Infraestrutura:
- Servidor (6 meses): R$ 300,00 × 6 = R$ 1.800,00
- Backups / Armazenamento extra: R$ 100,00
- Manutenção: 6 Meses - R$ 800,00 × 6 = R$ 4.800,00

### Lucro:
35% sobre o total

### **Valor Total do Projeto: R$ 35.775,00*

**Elite Motors**
- Sistema completo de concessionária com foco na experiência do usuário e eficiência administrativa.
