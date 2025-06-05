Pasta para armazenar uploads organizados por categoria.

ESTRUTURA ORGANIZADA:
uploads/
├── cars/              # Imagens dos veículos
│   ├── 6838dfb0a0f63.jpg
│   └── ...outros arquivos
├── users/             # Avatars dos usuários (futuro)
├── temp/              # Uploads temporários
└── .htaccess          # Segurança principal

ESPECIFICAÇÕES:
- Formatos aceitos: JPG, JPEG, PNG, GIF, WEBP
- Tamanho máximo: 5MB
- Resolução recomendada: 800x600px ou similar

SEGURANÇA:
- Cada subpasta tem seu próprio .htaccess
- Execução de scripts bloqueada
- Apenas arquivos de imagem permitidos

IMPORTANTE: 
- Esta pasta deve ter permissões de escrita
- No Windows/XAMPP geralmente já vem configurada
- Em servidores Linux, configure: chmod 755 uploads/
