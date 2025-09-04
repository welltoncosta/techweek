<?php
// mail.php - Versão atualizada para aceitar JSON

// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'u686345830_techweek_utfpr';
$username = 'usuario';
$password = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Manter compatibilidade com o código antigo (método GET)
$email = $_GET["email"] ?? '';

if (!empty($email)) {
    // Código original para recuperação de senha (mantido para compatibilidade)
    $sql = mysqli_query($c, "SELECT nome, hash FROM participantes WHERE email='$email'") or die (mysqli_error());
    $cont = mysqli_num_rows($sql);
    
    if ($cont == 0) {
        session_start();
        $_SESSION["m"] = "O Email fornecido não está cadastrado. Tente outro ou cadastre-se ao lado.";
        header("location: ../recuperarSenha.php?erro=1");
    } else {
        $mostra = mysqli_fetch_array($sql);
        $destinatario = $email;
        $nome = $mostra["nome"];
        $hash = $mostra["hash"];

        $assunto = "Alteração de senha - TechWeek Francisco Beltrão";
            
        $mensagem = "Prezado(a) ".$nome.",<br><br>recebemos uma solicitação de alteração de senha no sistema da TechWeek Francisco Beltrão. Por motivos de segurança, estamos enviando este email para ter certeza que foi você mesmo quem solicitou a recuperação de senha. Clique no link abaixo para inserir uma nova senha.<br><br><a href='https://techweek.typexsistemas.com.br/nova_senha.php?hash=".$hash."'>https://techweek.typexsistemas.com.br/nova_senha.php?hash=".$hash."</a> <br><br>Atenciosamente,<br><br>Comissão Organizadora da TechWeek Francisco Beltrão 2025";
        
        // Usar a nova função de enviar email
        enviar_email($destinatario, $assunto, $mensagem);
    }
}

function enviar_email($destinatario, $assunto, $mensagem) {
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
    
    $mail->addAddress($destinatario, '');
    
    $mail->Subject = $assunto;
    $mail->msgHTML($mensagem);
    
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    } else {
        echo 'Mensagem Enviada para ' . $destinatario . '!';
        header("location: ../index.php?email=$destinatario&m=email_senha_enviado#login");
        return true;
    }
}
?>