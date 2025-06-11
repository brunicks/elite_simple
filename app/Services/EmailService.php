<?php

require_once __DIR__ . '/../../libs/PHPMailer.php';
require_once __DIR__ . '/../../libs/SMTP.php';
require_once __DIR__ . '/../../libs/Exception.php';
require_once __DIR__ . '/../Config/EmailConfig.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    private $config;
    
    public function __construct() {
        $this->config = EmailConfig::getConfig();
        $this->setupMailer();
    }
      private function setupMailer() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->config['host'];
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->config['username'];
            $this->mailer->Password   = $this->config['password'];
            $this->mailer->SMTPSecure = $this->config['encryption'];
            $this->mailer->Port       = $this->config['port'];
            
            // Default from
            $this->mailer->setFrom($this->config['from_email'], $this->config['from_name']);
            
            // Encoding
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->isHTML(true);
            
        } catch (Exception $e) {
            error_log("Email setup error: " . $e->getMessage());
        }
    }
    
    public function sendPasswordResetEmail($toEmail, $userName, $resetToken) {
        try {
            // Clear previous recipients
            $this->mailer->clearAddresses();
            
            // Recipient
            $this->mailer->addAddress($toEmail, $userName);
            
            // Content
            $resetLink = $this->getBaseUrl() . "/reset_password?token=" . $resetToken;
            
            $this->mailer->Subject = 'Redefinição de Senha - Elite Motors';
            $this->mailer->Body = $this->getPasswordResetTemplate($userName, $resetLink);
            $this->mailer->AltBody = $this->getPasswordResetTextTemplate($userName, $resetLink);
            
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    private function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['REQUEST_URI']);
        return $protocol . '://' . $host . $path;
    }
    
    private function getPasswordResetTemplate($userName, $resetLink) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #1a1a1a; color: #ffffff; }
                .container { max-width: 600px; margin: 0 auto; background-color: #2d2d2d; }
                .header { background: linear-gradient(135deg, #d4af37, #f4e4a6); padding: 30px; text-align: center; }
                .header h1 { margin: 0; color: #000000; font-size: 28px; font-weight: bold; }
                .content { padding: 40px 30px; }
                .content h2 { color: #d4af37; margin-bottom: 20px; }
                .content p { line-height: 1.6; margin-bottom: 20px; color: #cccccc; }
                .button { display: inline-block; background: linear-gradient(135deg, #d4af37, #f4e4a6); color: #000000; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                .button:hover { background: linear-gradient(135deg, #b8941f, #d4af37); }
                .footer { background-color: #1a1a1a; padding: 20px; text-align: center; color: #888888; font-size: 12px; }
                .warning { background-color: #3d2914; border-left: 4px solid #d4af37; padding: 15px; margin: 20px 0; }
                .warning p { margin: 0; color: #f4e4a6; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Elite Motors</h1>
                </div>
                <div class='content'>
                    <h2>Redefinição de Senha</h2>
                    <p>Olá <strong>" . htmlspecialchars($userName) . "</strong>,</p>
                    <p>Recebemos uma solicitação para redefinir a senha da sua conta na Elite Motors.</p>
                    <p>Para criar uma nova senha, clique no botão abaixo:</p>
                    <p style='text-align: center;'>
                        <a href='" . htmlspecialchars($resetLink) . "' class='button'>Redefinir Senha</a>
                    </p>
                    <div class='warning'>
                        <p><strong>⚠️ Importante:</strong></p>
                        <p>• Este link é válido por apenas 1 hora</p>
                        <p>• Se você não solicitou esta redefinição, ignore este email</p>
                        <p>• Nunca compartilhe este link com outras pessoas</p>
                    </div>
                    <p>Se o botão não funcionar, copie e cole o link abaixo no seu navegador:</p>
                    <p style='word-break: break-all; background-color: #1a1a1a; padding: 10px; border-radius: 3px; font-family: monospace;'>" . htmlspecialchars($resetLink) . "</p>
                </div>
                <div class='footer'>
                    <p>Este é um email automático, não responda.</p>
                    <p>&copy; 2024 Elite Motors. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    private function getPasswordResetTextTemplate($userName, $resetLink) {
        return "Elite Motors - Redefinição de Senha

Olá " . $userName . ",

Recebemos uma solicitação para redefinir a senha da sua conta na Elite Motors.

Para criar uma nova senha, acesse o link abaixo:
" . $resetLink . "

IMPORTANTE:
- Este link é válido por apenas 1 hora
- Se você não solicitou esta redefinição, ignore este email
- Nunca compartilhe este link com outras pessoas

Se você não conseguir clicar no link, copie e cole o endereço completo no seu navegador.

Este é um email automático, não responda.

© 2024 Elite Motors. Todos os direitos reservados.";
    }
    
    public function testConnection() {
        try {
            $this->mailer->smtpConnect();
            $this->mailer->smtpClose();
            return true;
        } catch (Exception $e) {
            error_log("SMTP connection test failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function isConfigured() {
        return !empty($this->config['username']) && 
               !empty($this->config['password']) && 
               !empty($this->config['from_email']);
    }
    
    public function getConfigStatus() {
        return [
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'username_set' => !empty($this->config['username']),
            'password_set' => !empty($this->config['password']),
            'from_email_set' => !empty($this->config['from_email']),
            'configured' => $this->isConfigured()
        ];
    }
}
