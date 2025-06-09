<?php

class EmailConfig {    // SMTP Configuration - Configure com seus dados
    public static $smtp = [
        // GMAIL - Para enviar emails reais
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'noreplyelitemotors@gmail.com', // ← CONFIGURE: Seu email Gmail
        'password' => 'lnwxhbxngmybczsm', // ← CONFIGURE: Sua senha de app Gmail (16 caracteres)
        'from_email' => 'noreplyelitemotors@gmail.com', // ← CONFIGURE: Email do remetente (mesmo que username)
        'from_name' => 'Elite Motors - Sistema de Carros'
    ];
    
    // EXEMPLOS DE CONFIGURAÇÃO:
      // 📧 MAILTRAP (RECOMENDADO - Para desenvolvimento)
    // 1. Registre-se grátis em https://mailtrap.io
    // 2. Crie um inbox de teste
    // 3. Copie as credenciais SMTP:
    /*
    'host' => 'sandbox.smtp.mailtrap.io',
    'port' => 2525,
    'encryption' => 'tls',
    'username' => 'seu_username_mailtrap',     // ← Do seu inbox Mailtrap
    'password' => 'sua_senha_mailtrap',        // ← Do seu inbox Mailtrap
    'from_email' => 'noreply@elitemotors.com', // ← Email fictício (pode ser qualquer)
    'from_name' => 'Elite Motors',
    */
    
    // 📧 GMAIL (Usando sua conta pessoal para enviar como Elite Motors)
    /*
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'seuemail@gmail.com',        // ← Seu Gmail real
    'password' => 'suasenhadesapp',            // ← Senha de app do Gmail
    'from_email' => 'noreply@elitemotors.com', // ← Aparece como remetente
    'from_name' => 'Elite Motors',
    */
    
    // 📧 OUTLOOK/HOTMAIL
    /*
    'host' => 'smtp-mail.outlook.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'seuemail@outlook.com',
    'password' => 'suasenha',
    'from_email' => 'seuemail@outlook.com',
    */
    
    // 📧 MAILTRAP (Para desenvolvimento - captura emails sem enviar)
    // Crie conta em mailtrap.io, copie credenciais do seu inbox:
    /*
    'host' => 'sandbox.smtp.mailtrap.io',
    'port' => 2525,
    'encryption' => 'tls',
    'username' => 'seu_username_mailtrap',
    'password' => 'sua_senha_mailtrap',
    'from_email' => 'noreply@elitemotors.com',
    */
    
    public static function getConfig() {
        return self::$smtp;
    }
}
