<?php
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

include('conexao.php');

$usuario = $_SESSION['usuario'];
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $instituicao = $_POST['instituicao'] ?? '';
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    try {
        // Atualizar dados básicos
        $stmt = $pdo->prepare("UPDATE participantes SET nome = :nome, email = :email, telefone = :telefone, instituicao = :instituicao WHERE id = :id");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':telefone' => $telefone,
            ':instituicao' => $instituicao,
            ':id' => $usuario['id']
        ]);
        
        // Verificar se há alteração de senha
        if (!empty($senha_atual)) {
            // Verificar se a senha atual está correta
            $stmt = $pdo->prepare("SELECT senha FROM participantes WHERE id = :id");
            $stmt->execute([':id' => $usuario['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($senha_atual, $result['senha'])) {
                if ($nova_senha === $confirmar_senha) {
                    // Atualizar a senha
                    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE participantes SET senha = :senha WHERE id = :id");
                    $stmt->execute([':senha' => $nova_senha_hash, ':id' => $usuario['id']]);
                    
                    $response['message'] = "Dados e senha alterados com sucesso!";
                } else {
                    $response['success'] = false;
                    $response['message'] = "As novas senhas não coincidem!";
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['success'] = false;
                $response['message'] = "Senha atual incorreta!";
                echo json_encode($response);
                exit;
            }
        }
        
        $response['success'] = true;
        if (!isset($response['message'])) {
            $response['message'] = "Dados alterados com sucesso!";
        }
        
        // Atualizar dados na sessão
        $_SESSION['usuario']['nome'] = $nome;
        $_SESSION['usuario']['email'] = $email;
        $_SESSION['usuario']['telefone'] = $telefone;
        $_SESSION['usuario']['instituicao'] = $instituicao;
        
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['message'] = "Erro ao atualizar dados: " . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = "Método não permitido.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
