-- Adicionar campos para reset de senha na tabela usuarios
ALTER TABLE usuarios ADD COLUMN reset_token VARCHAR(255) NULL AFTER ativo;
ALTER TABLE usuarios ADD COLUMN reset_token_expires TIMESTAMP NULL AFTER reset_token;
