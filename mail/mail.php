<?php

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';

// Verificar se é uma requisição POST com JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $email = $input['email'] ?? '';
    $assunto = $input['assunto'] ?? '';
    $mensagem = $input['mensagem'] ?? '';
    
    if (!empty($email) && !empty($assunto) && !empty($mensagem)) {
        // Configurações do email
        $remetente = "TechWeek Francisco Beltrão 2025";
        
        $mail = new PHPMailer();
        
        $mail->isSMTP();
        $mail->CharSet = "utf-8"; 
        $mail->SMTPDebug = SMTP::DEBUG_OFF; // Alterado para OFF em produção
        $mail->Host = 'smtp.utfpr.edu.br';
        $mail->SMTPSecure = 'ssl';    
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'techweek-fb';
        $mail->Password = 'SemanaAcademica@00';
        $mail->setFrom('techweek-fb@utfpr.edu.br', $remetente);
        
        $mail->addAddress($email, '');
        
        $mail->Subject = $assunto;
        $mail->msgHTML($mensagem);
        
        // Enviar email
        if (!$mail->send()) {
            echo json_encode(['success' => false, 'message' => 'Erro ao enviar email: ' . $mail->ErrorInfo]);
        } else {
            echo json_encode(['success' => true, 'message' => 'Email enviado com sucesso para ' . $email]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos para envio de email']);
    }
    exit;
}

?>