<?php
session_start();


// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']) || $_SESSION['usuario_administrador'] != 1) {
    header("location: index.html#login");
    exit;
}

// Incluir arquivo de conexão
include("conexao.php");


// Processar ações do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $response = ['success' => false, 'message' => 'Ação não reconhecida'];

    // Verificar token CSRF (simplificado para este exemplo)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response['message'] = 'Token de segurança inválido';
        echo json_encode($response);
        exit;
    }
    
    // Ação: Editar participante
    if (isset($_POST['action']) && $_POST['action'] === 'editar_participante') {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $cpf = $_POST['cpf'];
        $telefone = $_POST['telefone'];
        $instituicao = $_POST['instituicao'];
        $tipo = $_POST['tipo'];
    
    
        try {
            $stmt = $pdo->prepare("UPDATE participantes SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, instituicao = :instituicao, tipo = :tipo WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':cpf' => $cpf,
                ':telefone' => $telefone,
                ':instituicao' => $instituicao,
                ':tipo' => $tipo,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Participante atualizado com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar participante: ' . $e->getMessage();
        }
        
        echo json_encode($response);
    }
    
    // Ação: Buscar participante
    
    if (isset($_POST['action']) && $_POST['action'] === 'buscar_participante') {
    
        $id = $_POST['id'];
    
        try {
            $stmt = $pdo->prepare("SELECT * FROM participantes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $participante = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($participante) {
                $response['success'] = true;
                $response['participante'] = $participante;
            } else {
                $response['message'] = 'Participante não encontrado';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao buscar participante: ' . $e->getMessage();
        }
        
        echo json_encode($response); 
     }

    // Ação: Buscar atividade
    if (isset($_POST['action']) && $_POST['action'] === 'buscar_atividade') {
        $id = $_POST['id'];
    
        try {
            $stmt = $pdo->prepare("SELECT * FROM atividades WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $atividade = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($atividade) {
                $response['success'] = true;
                $response['atividade'] = $atividade;
            } else {
                $response['message'] = 'Atividade não encontrada';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao buscar atividade: ' . $e->getMessage();
        }
        echo json_encode($response);
    }

    // Ação: Visualizar comprovante
    if (isset($_POST['action']) && $_POST['action'] === 'visualizar_comprovante') {
         $id = $_POST['id'];
    
        try {
            $stmt = $pdo->prepare("SELECT * FROM comprovantes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $comprovante = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($comprovante) {
                $response['success'] = true;
                $response['arquivo'] = $comprovante['arquivo'];
                $response['tipo_arquivo'] = pathinfo($comprovante['arquivo'], PATHINFO_EXTENSION);
            } else {
                $response['message'] = 'Comprovante não encontrado';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao buscar comprovante: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
    // Ação: Alternar status de administrador
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_admin') {
    
        $id = $_POST['id'];
        $administrador = $_POST['administrador'];
        
        if($_POST["administrador"]){ $tipo="administrador";}
        else if(!$_POST["administrador"]){ $tipo="participante";}
        try {
            $stmt = $pdo->prepare("UPDATE participantes SET administrador = :administrador, tipo = :tipo WHERE id = :id");
            $stmt->execute([
                ':administrador' => $administrador,
                ':tipo' => $tipo,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Status de administrador atualizado';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar status: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
    // Ação: Cadastrar atividade
    if (isset($_POST['action']) && $_POST['action'] === 'cadastrar_atividade') {
        $titulo = $_POST['titulo'];
        $tipo = $_POST['tipo'];
        $palestrante = $_POST['palestrante'];
        $local = $_POST['local'];
        $data = $_POST['data'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fim = $_POST['hora_fim'];
        $vagas = $_POST['vagas'];
        
        try {
            $stmt = $pdo->prepare("INSERT INTO atividades (titulo, tipo, palestrante, sala, data, hora_inicio, hora_fim, vagas, ativa) VALUES (:titulo, :tipo, :palestrante, :sala, :data, :hora_inicio, :hora_fim, :vagas, 1)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':palestrante' => $palestrante,
                ':sala' => $local,
                ':data' => $data,
                ':hora_inicio' => $hora_inicio,
                ':hora_fim' => $hora_fim,
                ':vagas' => $vagas
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Atividade cadastrada com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao cadastrar atividade: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
    // Ação: Editar atividade
    if (isset($_POST['action']) && $_POST['action'] === 'editar_atividade') {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $tipo = $_POST['tipo'];
        $palestrante = $_POST['palestrante'];
        $local = $_POST['local'];
        $data = $_POST['data'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fim = $_POST['hora_fim'];
        $vagas = $_POST['vagas'];
        
        try {
            $stmt = $pdo->prepare("UPDATE atividades SET titulo = :titulo, tipo = :tipo, palestrante = :palestrante, sala = :sala, data = :data, horario = :horario, hora_inicio = :hora_inicio, vagas = :vagas WHERE id = :id");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':palestrante' => $palestrante,
                ':sala' => $local,
                ':data' => $data,
                ':horario' => $hora_inicio . ' - ' . $hora_fim,
                ':hora_inicio' => $hora_inicio,
                ':vagas' => $vagas,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Atividade atualizada com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar atividade: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
    // Ação: Registrar presença
    if (isset($_POST['action']) && $_POST['action'] === 'registrar_presenca') {
        $atividade_id = $_POST['atividade_id'];
        $codigo_barras = $_POST['codigo_barras'];
        
        try {
            // Buscar participante pelo código de barras
            $stmt = $pdo->prepare("SELECT * FROM participantes WHERE codigo_barra = :codigo_barra");
            $stmt->execute([':codigo_barra' => $codigo_barras]);
            $participante = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($participante) {
                $participante_id = $participante['id'];
                
                // Verificar se já registrou presença
                $stmt = $pdo->prepare("SELECT id FROM presencas WHERE id_participante = :participante_id AND id_atividade = :atividade_id");
                $stmt->execute([
                    ':participante_id' => $participante_id,
                    ':atividade_id' => $atividade_id
                ]);
                
                if ($stmt->rowCount() === 0) {
                    // Registrar presença
                    $stmt = $pdo->prepare("INSERT INTO presencas (id_participante, id_atividade, data_hora) VALUES (:participante_id, :atividade_id, NOW())");
                    $stmt->execute([
                        ':participante_id' => $participante_id,
                        ':atividade_id' => $atividade_id
                    ]);
                    
                    $response['success'] = true;
                    $response['message'] = 'Presença registrada com sucesso';
                } else {
                    $response['message'] = 'Presença já registrada anteriormente';
                }
            } else {
                $response['message'] = 'Participante não encontrado com este código de barras';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao registrar presença: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
    // Ação: Validar pagamento
    if (isset($_POST['action']) && $_POST['action'] === 'validar_pagamento') {
        $id = $_POST['id'];        
        $aprovado = $_POST['aprovado'];
        
        try {
            $status = $aprovado ? 'aprovado' : 'rejeitado';
            $stmt = $pdo->prepare("UPDATE comprovantes SET status = :status, data_avaliacao = NOW() WHERE id = :id");
            $stmt->execute([
                ':status' => $status,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Pagamento validado com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao validar pagamento: ' . $e->getMessage();
        }
        echo json_encode($response);
    }
    
// Ação: Cadastrar participante
if (isset($_POST['action']) && $_POST['action'] === 'cadastrar_participante') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $instituicao = $_POST['instituicao'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $voucher = $_POST['voucher'];
    $isento_pagamento = isset($_POST['isento_pagamento']) ? 1 : 0;
    
    // Gerar código de barras único
    $codigo_barra = uniqid('TW');
    
    try {
        $stmt = $pdo->prepare("INSERT INTO participantes (nome, email, cpf, telefone, instituicao, senha, tipo, codigo_barra, voucher, isento_pagamento) VALUES (:nome, :email, :cpf, :telefone, :instituicao, :senha, :tipo, :codigo_barra, :voucher, :isento_pagamento)");
        $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':cpf' => $cpf,
            ':telefone' => $telefone,
            ':instituicao' => $instituicao,
            ':senha' => $senha,
            ':tipo' => $tipo,
            ':codigo_barra' => $codigo_barra,
            ':voucher' => $voucher,
            ':isento_pagamento' => $isento_pagamento
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Participante cadastrado com sucesso';
    } catch (PDOException $e) {
        $response['message'] = 'Erro ao cadastrar participante: ' . $e->getMessage();
    }

    echo json_encode($response);
}

// Ação: Alternar status de atividade
if (isset($_POST['action']) && $_POST['action'] === 'toggle_ativa') {
    $id = $_POST['id'];
    $ativa = $_POST['ativa'] ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE atividades SET ativa = :ativa WHERE id = :id");
        $stmt->execute([
            ':ativa' => $ativa,
            ':id' => $id
        ]);
        
        $response['success'] = true;
        $response['message'] = 'Status da atividade atualizado';
    } catch (PDOException $e) {
        $response['message'] = 'Erro ao atualizar status: ' . $e->getMessage();
    }

	echo json_encode($response);
}


    // Se for uma requisição AJAX, retornar JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}



// Gerar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
