<?php
header('Content-Type: application/json');
session_start();

// Incluir e obter a conexão com o banco de dados
$pdo = include("conexao.php");

// Sanitizar entrada de dados
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Validar CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Gerar token seguro
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Processar a requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = isset($input['action']) ? sanitizeInput($input['action']) : '';
    
    switch ($action) {
        case 'login':
            processarLogin($input, $pdo);
            break;
        case 'recuperar_senha':
            processarRecuperacaoSenha($input, $pdo);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Ação não reconhecida']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}

// Processar login
function processarLogin($data, $pdo) {
    $login = sanitizeInput($data['login']);
    $senha = sanitizeInput($data['senha']);
    
    if (empty($login) || empty($senha)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos']);
        return;
    }
    
    try {
        // Verificar se o login é email ou CPF
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $campo = 'email';
        } else {
            // Remove caracteres não numéricos para CPF
            $login = preg_replace('/[^0-9]/', '', $login);
            if (validarCPF($login)) {
                $campo = 'cpf';
            } else {
                echo json_encode(['success' => false, 'message' => 'CPF inválido']);
                return;
            }
        }
        
        // Buscar usuário
        $stmt = $pdo->prepare("SELECT id, administrador, tipo_inscricao, lote_inscricao, nome, cpf, email, telefone, senha, instituicao, data_cadastro FROM participantes WHERE $campo = :login");
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar senha
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario'] = $usuario;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_tipo_inscricao'] = $usuario['tipo_inscricao'];
                $_SESSION['usuario_lote_inscricao'] = $usuario['lote_inscricao'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_telefone'] = $usuario['telefone'];
                $_SESSION['usuario_administrador'] = $usuario['administrador'];
                $_SESSION['logado'] = true;
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Login realizado com sucesso!',
                    'redirect' => 'painel.php'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuário não encontrado']);
        }
    } catch (PDOException $e) {
        error_log("Erro no login: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
    }
}

// Processar recuperação de senha
function processarRecuperacaoSenha($data, $pdo) {
    $email = sanitizeInput($data['email']);
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, informe um email válido']);
        return;
    }
    
    try {
        // Verificar se o email existe
        $stmt = $pdo->prepare("SELECT id, nome FROM participantes WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Gerar token de recuperação
            $token = generateToken();
            $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token válido por 1 hora
            
            // Salvar token no banco de dados
            $stmt = $pdo->prepare("UPDATE participantes SET token_recuperacao = :token, expiracao_token = :expiracao WHERE id = :id");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':expiracao', $expiracao, PDO::PARAM_STR);
            $stmt->bindParam(':id', $usuario['id'], PDO::PARAM_INT);
            $stmt->execute();
            
            // Preparar dados para o email
            $mailData = [
                'email' => $email,
                'assunto' => 'Recuperação de Senha - TechWeek',
                'mensagem' => gerarMensagemRecuperacao($usuario['nome'], $token)
            ];
            
            // Chamar o script de email
            $url = 'https://wellton.com.br/techweek/mail/mail.php';

            echo $url;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mailData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                echo json_encode(['success' => true, 'message' => 'Email de recuperação enviado com sucesso! Verifique sua caixa de entrada.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao enviar email de recuperação.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email não encontrado em nosso sistema']);
        }
    } catch (PDOException $e) {
        error_log("Erro na recuperação de senha: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
    }
}

// Gerar mensagem de recuperação de senha
function gerarMensagemRecuperacao($nome, $token) {
    $link = 'https://wellton.com.br/techweek/login_auto.php?token=' . $token;
    
    $mensagem = "Prezado(a) $nome,<br><br>";
    $mensagem .= "Recebemos uma solicitação de recuperação de senha para sua conta na TechWeek.<br><br>";
    $mensagem .= "Clique no link abaixo para acessar automaticamente sua conta e redefinir sua senha:<br>";
    $mensagem .= "<a href='$link'>$link</a><br><br>";
    $mensagem .= "Este link expirará em 1 hora.<br><br>";
    $mensagem .= "Se você não solicitou esta recuperação, ignore este email.<br><br>";
    $mensagem .= "Atenciosamente,<br>Equipe TechWeek";
    
    return $mensagem;
}
?>