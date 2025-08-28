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
        $voucher = $_POST['voucher'];
        $isento_pagamento = isset($_POST['isento_pagamento']) ? 1 : 0;
        
        try {
            $stmt = $pdo->prepare("UPDATE participantes SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, instituicao = :instituicao, tipo = :tipo, voucher = :voucher, isento_pagamento = :isento_pagamento WHERE id = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':cpf' => $cpf,
                ':telefone' => $telefone,
                ':instituicao' => $instituicao,
                ':tipo' => $tipo,
                ':voucher' => $voucher,
                ':isento_pagamento' => $isento_pagamento,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Participante atualizado com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar participante: ' . $e->getMessage();
        }
    }
    
    // Ação: Alternar status de administrador
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_admin') {
        $id = $_POST['id'];
        $administrador = $_POST['administrador'] ? 1 : 0;
        
        try {
            $stmt = $pdo->prepare("UPDATE participantes SET administrador = :administrador WHERE id = :id");
            $stmt->execute([
                ':administrador' => $administrador,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Status de administrador atualizado';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar status: ' . $e->getMessage();
        }
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
            $stmt = $pdo->prepare("INSERT INTO atividades (titulo, tipo, palestrante, sala, data, horario, hora_inicio, hora_fim, vagas, ativa) VALUES (:titulo, :tipo, :palestrante, :sala, :data, :horario, :hora_inicio, :hora_fim, :vagas, 1)");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':palestrante' => $palestrante,
                ':sala' => $local,
                ':data' => $data,
                ':horario' => $hora_inicio . ' - ' . $hora_fim,
                ':hora_inicio' => $hora_inicio,
                ':hora_fim' => $hora_fim,
                ':vagas' => $vagas
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Atividade cadastrada com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao cadastrar atividade: ' . $e->getMessage();
        }
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
            $stmt = $pdo->prepare("UPDATE atividades SET titulo = :titulo, tipo = :tipo, palestrante = :palestrante, sala = :sala, data = :data, horario = :horario, hora_inicio = :hora_inicio, hora_fim = :hora_fim, vagas = :vagas WHERE id = :id");
            $stmt->execute([
                ':titulo' => $titulo,
                ':tipo' => $tipo,
                ':palestrante' => $palestrante,
                ':sala' => $local,
                ':data' => $data,
                ':horario' => $hora_inicio . ' - ' . $hora_fim,
                ':hora_inicio' => $hora_inicio,
                ':hora_fim' => $hora_fim,
                ':vagas' => $vagas,
                ':id' => $id
            ]);
            
            $response['success'] = true;
            $response['message'] = 'Atividade atualizada com sucesso';
        } catch (PDOException $e) {
            $response['message'] = 'Erro ao atualizar atividade: ' . $e->getMessage();
        }
    }
    
    // Ação: Registrar presença
    if (isset($_POST['action']) && $_POST['action'] === 'registrar_presenca') {
        $atividade_id = $_POST['atividade_id'];
        $codigo_barras = $_POST['codigo_barras'];
        
        try {
            // Buscar participante pelo código de barras
            $stmt = $pdo->prepare("SELECT id FROM participantes WHERE codigo_barra = :codigo_barra");
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
    }
    
    // Ação: Validar pagamento
    if (isset($_POST['action']) && $_POST['action'] === 'validar_pagamento') {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
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
        $isento_pagamento = 0; // Removido do formulário de cadastro
        
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

// Buscar dados para o dashboard
try {
    // Total de participantes
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM participantes");
    $stmt->execute();
    $total_participantes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total de atividades
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM atividades WHERE ativa = '1'");
    $stmt->execute();
    $total_atividades = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Total de presenças
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM presencas");
    $stmt->execute();
    $total_presencas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Comprovantes pendentes
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM comprovantes WHERE status = 'pendente'");
    $stmt->execute();
    $comprovantes_pendentes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Participantes por tipo
    $stmt = $pdo->prepare("SELECT tipo, COUNT(*) as quantidade FROM participantes GROUP BY tipo");
    $stmt->execute();
    $participantes_por_tipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Atividades por tipo
    $stmt = $pdo->prepare("SELECT tipo, COUNT(*) as quantidade FROM atividades WHERE ativa = '1' GROUP BY tipo");
    $stmt->execute();
    $atividades_por_tipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lista de participantes
    $stmt = $pdo->prepare("SELECT * FROM participantes ORDER BY nome");
    $stmt->execute();
    $participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lista de atividades
    $stmt = $pdo->prepare("SELECT * FROM atividades ORDER BY data, hora_inicio");
    $stmt->execute();
    $atividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lista de presenças
    $stmt = $pdo->prepare("SELECT p.nome, a.titulo, pr.data_hora 
                          FROM presencas pr 
                          JOIN participantes p ON pr.id_participante = p.id 
                          JOIN atividades a ON pr.id_atividade = a.id 
                          ORDER BY pr.data_hora DESC");
    $stmt->execute();
    $presencas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lista de comprovantes
    $stmt = $pdo->prepare("SELECT c.*, p.nome as participante_nome 
                          FROM comprovantes c 
                          JOIN participantes p ON c.participante_id = p.id 
                          ORDER BY c.data_envio DESC");
    $stmt->execute();
    $comprovantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}

// Função para formatar data
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

// Função para formatar data e hora
function formatarDataHora($dataHora) {
    return date('d/m/Y H:i', strtotime($dataHora));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - 1ª TechWeek</title>
    <link rel="shortcut icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%2300FF00' d='M13 2.03v2.02c4.39.54 7.5 4.53 6.96 8.92c-.46 3.64-3.32 6.53-6.96 6.96v2c5.5-.55 9.5-5.43 8.95-10.93c-.45-4.75-4.22-8.5-8.95-8.97m-2 .03c-1.95.19-3.81.94-5.33 2.2L7.1 5.74c1.12-.9 2.47-1.48 3.9-1.68v-2M4.26 5.67A9.885 9.885 0 0 0 2.05 11h2c.19-1.42.75-2.77 1.64-3.9L4.26 5.67M2.06 13c.2 1.96.97 3.81 2.21 5.33l1.42-1.43A8.002 8.002 0 0 1 4.06 13h-2m5.04 5.37l-1.43 1.37A9.994 9.994 0 0 0 11 22v-2a8.002 8.002 0 0 1-3.9-1.63m9.33-12.37l-1.59 1.59L16 10l-4-4V3l-1 1l4 4l.47-.53l-1.53-1.53l1.59-1.59l.94.94z'/%3E%3C/svg%3E" type="image/svg+xml">

    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --black: #000000;
            --neon-green: #00FF00;
            --tech-green: #00BF63;
            --light-gray: #D9D9D9;
            --white: #FFFFFF;
            --dark-gray: #1A1A1A;
            --bg-color: #000000;
            --text-color: #FFFFFF;
            --card-bg: rgba(26, 26, 26, 0.7);
            --border-color: #00BF63;
            --accent-color: #00BF63;
            --accent-hover: #00FF00;
            --error-color: #ff3860;
            --success-color: #09c372;
            --input-icon-color: #FFFFFF;
        }
        
        .light-theme {
            --bg-color: #f8f9fa;
            --text-color: #2d3748;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: #2d7d5a;
            --black: #ffffff;
            --light-gray: #718096;
            --dark-gray: #e2e8f0;
            --accent-color: #2d7d5a;
            --accent-hover: #38a169;
            --neon-green: #2d7d5a;
            --tech-green: #38a169;
            --error-color: #e53e3e;
            --success-color: #38a169;
            --input-icon-color: #2d3748;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
            font-size: 16px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(0, 191, 99, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0, 255, 0, 0.1) 0%, transparent 20%);
            pointer-events: none;
            z-index: -1;
        }
        
        .light-theme body::before {
            background: 
                radial-gradient(circle at 10% 20%, rgba(45, 125, 90, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(56, 161, 105, 0.05) 0%, transparent 20%);
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header */
        header {
            background-color: var(--black);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.2);
        }
        
        .light-theme header {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 80px;
            transition: all 0.3s ease;
        }
        
        .logo-dark {
            display: block;
        }
        
        .logo-light {
            display: none;
        }
        
        .light-theme .logo-dark {
            display: none;
        }
        
        .light-theme .logo-light {
            display: block;
        }
        
        .logo:hover img {
            filter: drop-shadow(0 0 8px var(--accent-color));
        }
        
        .event-title {
            margin-left: 12px;
        }
        
        .event-title h1 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-color);
            line-height: 1.3;
        }
        
        .event-title span {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .header-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .theme-toggle {
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            background-color: rgba(45, 125, 90, 0.15);
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-btn {
            background: linear-gradient(45deg, var(--tech-green), var(--neon-green));
            color: var(--black);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .light-theme .user-btn {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            color: white;
        }
        
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            display: none;
            z-index: 100;
        }
        
        .user-dropdown.active {
            display: block;
        }
        
        .user-dropdown a {
            display: block;
            color: var(--text-color);
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .user-dropdown a:hover {
            background-color: rgba(0, 191, 99, 0.2);
            color: var(--neon-green);
        }
        
        .light-theme .user-dropdown a:hover {
            background-color: rgba(45, 125, 90, 0.15);
            color: var(--accent-color);
        }
        
        /* Menu */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
        }
        
        .menu-toggle:hover {
            background-color: rgba(45, 125, 90, 0.15);
        }
        
        .menu {
            display: flex;
            flex-direction: row;
            list-style: none;
            background-color: transparent;
            position: relative;
            top: auto;
            left: auto;
            z-index: 100;
            padding: 0;
            border-top: none;
            box-shadow: none;
            width: auto;
        }
        
        .light-theme .menu {
            box-shadow: none;
        }
        
        .menu.active {
            display: flex;
        }
        
        .menu li {
            width: auto;
        }
        
        .menu a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 15px;
            display: block;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1rem;
        }
        
        .menu a:hover {
            background-color: rgba(45, 125, 90, 0.15);
            color: var(--accent-color);
        }
        
        .menu a.vermelho {
            color: var(--accent-color);
            font-weight: 700;
        }
        
        /* Admin Panel */
        .admin-panel {
            display: grid;
            //grid-template-columns: 100% 1fr;
            min-height: calc(100vh - 180px);
            flex: 1;
        }
        
        .admin-sidebar {
            background-color: var(--black);
            padding: 20px;
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 104px; /* Altura do header */
            left: 0;
            width: 250px;
            height: calc(100vh - 104px);
            overflow-y: auto;
            z-index: 99;
        }
        
        .light-theme .admin-sidebar {
            background-color: var(--dark-gray);
        }
        
        .admin-sidebar h2 {
            color: var(--neon-green);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .light-theme .admin-sidebar h2 {
            color: var(--accent-color);
        }
        
        .admin-sidebar ul {
            list-style: none;
        }
        
        .admin-sidebar li {
            margin-bottom: 10px;
        }
        
        .admin-sidebar a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .admin-sidebar a:hover, .admin-sidebar a.active {
            background-color: rgba(0, 191, 99, 0.2);
            color: var(--neon-green);
        }
        
        .light-theme .admin-sidebar a:hover, .light-theme .admin-sidebar a.active {
            background-color: rgba(45, 125, 90, 0.15);
            color: var(--accent-color);
        }
        
        .admin-content {
            padding: 20px;
            overflow-y: auto;
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        
        .admin-section {
            display: none;
        }
        
        .admin-section.active {
            display: block;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--neon-green);
            font-weight: 600;
        }
        
        .light-theme .form-group label {
            color: var(--accent-color);
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            background-color: var(--card-bg);
            color: var(--text-color);
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--neon-green);
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
        }
        
        .light-theme .form-group input:focus,
        .light-theme .form-group select:focus,
        .light-theme .form-group textarea:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 10px rgba(45, 125, 90, 0.2);
        }
        
        /* Ícones para campos de data e hora */
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--input-icon-color);
            pointer-events: none;
            z-index: 2;
        }
        
        .input-with-icon input {
            padding-right: 40px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--tech-green), var(--neon-green));
            color: var(--black);
            border: none;
            border-radius: 5px;
            padding: 12px 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .light-theme .btn-primary {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--neon-green), var(--tech-green));
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .light-theme .btn-primary:hover {
            background: linear-gradient(45deg, var(--accent-hover), var(--accent-color));
            box-shadow: 0 0 10px rgba(45, 125, 90, 0.3);
        }
        
        .btn-small {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        /* Table Styles */
        .table-container {
            background: var(--card-bg);
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 15px;
            box-shadow: 0 0 20px rgba(0, 191, 99, 0.2);
            overflow-x: auto;
            margin-bottom: 30px;
        }
        
        .light-theme .table-container {
            box-shadow: 0 0 20px rgba(45, 125, 90, 0.1);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        .data-table th {
            background-color: rgba(0, 191, 99, 0.2);
            color: var(--neon-green);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            font-size: 1rem;
        }
        
        .light-theme .data-table th {
            background-color: rgba(45, 125, 90, 0.1);
            color: var(--accept-color);
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(217, 217, 217, 0.1);
            color: var(--light-gray);
            font-size: 0.95rem;
        }
        
        .light-theme .data-table td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: rgba(0, 191, 99, 0.05);
        }
        
        .light-theme .data-table tr:hover {
            background-color: rgba(45, 125, 90, 0.03);
        }
        
        /* Checkbox Styles */
        .admin-checkbox {
            display: inline-block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
        }
        
        .admin-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 5px;
        }
        
        .admin-checkbox:hover input ~ .checkmark {
            background-color: rgba(0, 191, 99, 0.1);
        }
        
        .admin-checkbox input:checked ~ .checkmark {
            background-color: var(--tech-green);
        }
        
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        
        .admin-checkbox input:checked ~ .checkmark:after {
            display: block;
        }
        
        .admin-checkbox .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
        
        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-participante {
            background-color: rgba(0, 191, 99, 0.2);
            color: var(--neon-green);
        }
        
        .badge-palestrante {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
        
        .badge-organizacao {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
        }
        
        .badge-coordenacao {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
        
        .badge-centro_academico {
            background-color: rgba(111, 66, 193, 0.2);
            color: #6f42c1;
        }
        
        .badge-typex {
            background-color: rgba(32, 201, 151, 0.2);
            color: #20c997;
        }
        
        .badge-apoyo {
            background-color: rgba(253, 126, 20, 0.2);
            color: #fd7e14;
        }
        
        /* Status Styles */
        .status-approved {
            color: var(--success-color);
        }
        
        .status-pending {
            color: #ffc107;
        }
        
        .status-rejected {
            color: var(--error-color);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.3);
            position: relative;
        }
        
        .light-theme .modal-content {
            box-shadow: 0 0 20px rgba(45, 125, 90, 0.2);
        }
        
        .modal h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        .light-theme .modal h3 {
            color: var(--accent-color);
        }
        
        .modal p {
            color: var(--light-gray);
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .modal button {
            padding: 10px 20px;
            background: linear-gradient(45deg, var(--tech-green), var(--neon-green));
            color: var(--black);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .light-theme .modal button {
            background: linear-gradient(45deg, var(--accent-color), var(--accent-hover));
            color: white;
        }
        
        .modal button:hover {
            background: linear-gradient(45deg, var(--neon-green), var(--tech-green));
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        .light-theme .modal button:hover {
            background: linear-gradient(45deg, var(--accent-hover), var(--accent-color));
            box-shadow: 0 0 10px rgba(45, 125, 90, 0.3);
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--error-color);
        }
        
        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .dashboard-card {
            background: var(--card-bg);
            border-radius: 10px;
            border: 1px solid var(--border-color);
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0, 191, 99, 0.2);
        }
        
        .light-theme .dashboard-card {
            box-shadow: 0 0 15px rgba(45, 125, 90, 0.1);
        }
        
        .dashboard-card i {
            font-size: 2.5rem;
            color: var(--neon-green);
            margin-bottom: 15px;
        }
        
        .light-theme .dashboard-card i {
            color: var(--accent-color);
        }
        
        .dashboard-card h3 {
            font-size: 1rem;
            margin-bottom: 10px;
            color: var(--text-color);
        }
        
        .dashboard-card p {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--neon-green);
        }
        
        .light-theme .dashboard-card p {
            color: var(--accent-color);
        }
        
        /* Footer */
        footer {
            background: var(--black);
            padding: 30px 0 15px;
            border-top: 1px solid var(--border-color);
            margin-top: auto;
        }
        
        .light-theme footer {
            background: var(--dark-gray);
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 25px;
        }
        
        .footer-logo {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .footer-logo img {
            max-width: 130px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .footer-logo-dark {
            display: block;
        }
        
        .footer-logo-light {
            display: none;
        }
        
        .light-theme .footer-logo-dark {
            display: none;
        }
        
        .light-theme .footer-logo-light {
            display: block;
        }
        
        .footer-info {
            text-align: center;
        }
        
        .footer-info h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
            font-size: 1.4rem;
        }
        
        .light-theme .footer-info h3 {
            color: var(--accent-color);
        }
        
        .footer-info p {
            margin-bottom: 8px;
            color: var(--light-gray);
            font-size: 0.95rem;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid rgba(0, 191, 99, 0.3);
            color: var(--light-gray);
            font-size: 0.85rem;
        }
        
        .light-theme .footer-bottom {
            border-top: 1px solid rgba(45, 125, 90, 0.2);
        }
        
        /* Message Styles */
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .message.sucesso {
            background-color: rgba(9, 195, 114, 0.2);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }
        
        .message.erro {
            background-color: rgba(255, 56, 96, 0.2);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }
        
        /* Flatpickr Custom Styles */
        .flatpickr-calendar {
            background: var(--card-bg);
            color: var(--text-color);
            border: 1px solid var(--border-color);
            box-shadow: 0 0 15px rgba(0, 191, 99, 0.2);
        }
        
        .flatpickr-day {
            color: var(--text-color);
        }
        
        .flatpickr-day:hover {
            background: var(--neon-green);
            color: black;
        }
        
        .flatpickr-day.today {
            border-color: var(--neon-green);
        }
        
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: var(--tech-green);
            border-color: var(--tech-green);
            color: black;
        }
        
        .flatpickr-time input, .flatpickr-time .flatpickr-am-pm {
            color: var(--text-color);
        }
        
        .flatpickr-time .numInputWrapper span.arrowUp:after {
            border-bottom-color: var(--text-color);
        }
        
        .flatpickr-time .numInputWrapper span.arrowDown:after {
            border-top-color: var(--text-color);
        }
        
        /* Ajustes para o tema claro */
        .light-theme .flatpickr-calendar {
            background: #fff;
            color: #2d3748;
            border: 1px solid #cbd5e0;
            box-shadow: 0 0 15px rgba(45, 125, 90, 0.1);
        }
        
        .light-theme .flatpickr-day {
            color: #2d3748;
        }
        
        .light-theme .flatpickr-day:hover {
            background: #e2e8f0;
        }
        
        .light-theme .flatpickr-day.today {
            border-color: var(--accent-color);
        }
        
        .light-theme .flatpickr-day.selected, .light-theme .flatpickr-day.startRange, .light-theme .flatpickr-day.endRange, .light-theme .flatpickr-day.selected.inRange, .light-theme .flatpickr-day.startRange.inRange, .light-theme .flatpickr-day.endRange.inRange, .light-theme .flatpickr-day.selected:focus, .light-theme .flatpickr-day.startRange:focus, .light-theme .flatpickr-day.endRange:focus, .light-theme .flatpickr-day.selected:hover, .light-theme .flatpickr-day.startRange:hover, .light-theme .flatpickr-day.endRange:hover, .light-theme .flatpickr-day.selected.prevMonthDay, .light-theme .flatpickr-day.startRange.prevMonthDay, .light-theme .flatpickr-day.endRange.prevMonthDay, .light-theme .flatpickr-day.selected.nextMonthDay, .light-theme .flatpickr-day.startRange.nextMonthDay, .light-theme .flatpickr-day.endRange.nextMonthDay {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        /* Linhas de atividades não confirmadas */
        .data-table tr.nao-confirmada {
            opacity: 0.6;
            background-color: rgba(255, 0, 0, 0.1);
        }
        
        /* Tabs */
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .tab-link {
            padding: 10px 20px;
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            font-weight: 600;
            border-bottom: 3px solid transparent;
        }
        
        .tab-link.active {
            border-bottom-color: var(--neon-green);
            color: var(--neon-green);
        }
        
        .light-theme .tab-link.active {
            border-bottom-color: var(--accent-color);
            color: var(--accent-color);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Media Queries */
        @media (max-width: 992px) {
            .admin-panel {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                display: none;
            }
            
            .admin-content {
                margin-left: 0;
                width: 100%;
            }
            
            .menu {
                display: none;
                flex-direction: column;
                width: 100%;
                background-color: var(--black);
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 100;
                padding: 10px 0;
                border-top: 1px solid var(--border-color);
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            }
            
            .light-theme .menu {
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            }
            
            .menu-toggle {
                display: flex;
            }
            
            .menu.active {
                display: flex;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .event-title {
                margin-left: 0;
                text-align: center;
            }
            
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="imagens/logo.jpg" alt="Logo Tech Week" class="logo-dark">
                    <img src="imagens/logo-light.jpg" alt="Logo Tech Week" class="logo-light">
                    <div class="event-title">
                        <h1>1ª TechWeek</h1>
                        <span>Painel de Administração</span>
                    </div>
                </div>
                
                <ul class="menu">
                    <li><a href="#dashboard" class="admin-nav active" data-section="dashboard">Dashboard</a></li>
                    <li><a href="#participantes" class="admin-nav" data-section="participantes">Participantes</a></li>
                    <li><a href="#atividades" class="admin-nav" data-section="atividades">Atividades</a></li>
                    <li><a href="#presencas" class="admin-nav" data-section="presencas">Presenças</a></li>
                    <li><a href="#comprovantes" class="admin-nav" data-section="comprovantes">Comprovantes</a></li>
                </ul>

                <div class="header-controls">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <div class="user-menu">
                        <button class="user-btn">
                            <i class="fas fa-user"></i>
                            <?php echo explode(" ", $_SESSION['usuario']['nome'])[0]; ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="index.html"><i class="fas fa-home"></i> Voltar ao Site</a>
                            <a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </div>
                    
                    <button class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="admin-panel">
        <div class="admin-sidebar">
            <h2>Painel de Admin</h2>
            <ul>
                <li><a href="#dashboard" class="admin-nav active" data-section="dashboard">Dashboard</a></li>
                <li><a href="#participantes" class="admin-nav" data-section="participantes">Participantes</a></li>
                <li><a href="#atividades" class="admin-nav" data-section="atividades">Atividades</a></li>
                <li><a href="#presencas" class="admin-nav" data-section="presencas">Presenças</a></li>
                <li><a href="#comprovantes" class="admin-nav" data-section="comprovantes">Comprovantes</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <!-- Mensagens para o usuário -->
            <div id="mensagem"></div>
            
            <!-- Seção Dashboard -->
            <section id="dashboard-section" class="admin-section active">
                <h2>Dashboard</h2>
                
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <i class="fas fa-users"></i>
                        <h3>Total de Participantes</h3>
                        <p><?php echo $total_participantes; ?></p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Total de Atividades</h3>
                        <p><?php echo $total_atividades; ?></p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Presenças Confirmadas</h3>
                        <p><?php echo $total_presencas; ?></p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Comprovantes Pendentes</h3>
                        <p><?php echo $comprovantes_pendentes; ?></p>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">Estatísticas por Tipo</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($participantes_por_tipo as $tipo): ?>
                            <tr>
                                <td><span class="badge badge-participante"><?php echo strtolower($tipo['tipo']); ?></span></td>
                                <td><?php echo $tipo['quantidade']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Seção de Participantes -->
            <section id="participantes-section" class="admin-section">
                <h2>Gerenciar Participantes</h2>
                
                <div class="tabs">
                    <button class="tab-link active" data-tab="cadastrar-participante">Cadastrar</button>
                    <button class="tab-link" data-tab="ver-participantes">Ver Participantes</button>
                </div>
                
                <div id="cadastrar-participante" class="tab-content active">
                    <h3>Cadastrar Novo Participante</h3>
                    <div class="table-container">
                        <form id="cadastrar-participante-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action" value="cadastrar_participante">
                            
                            <div class="form-group">
                                <label for="novo-nome" class="required">Nome Completo</label>
                                <input type="text" id="novo-nome" name="nome" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-email" class="required">E-mail</label>
                                <input type="email" id="novo-email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-cpf" class="required">CPF</label>
                                <input type="text" id="novo-cpf" name="cpf" required maxlength="14">
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-telefone">Telefone</label>
                                <input type="tel" id="novo-telefone" name="telefone">
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-instituicao">Instituição/Empresa</label>
                                <input type="text" id="novo-instituicao" name="instituicao">
                            </div>

                            <div class="form-group">
                                <label for="novo-senha" class="required">Senha</label>
                                <input type="password" id="novo-senha" name="senha" required minlength="6">
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-repita_senha" class="required">Repita a Senha</label>
                                <input type="password" id="novo-repita_senha" name="repita_senha" required minlength="6">
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-tipo">Tipo</label>
                                <select id="novo-tipo" name="tipo" required>
                                    <option value="participante">Participante</option>
                                    <option value="organizacao">Organização</option>
                                    <option value="apoio">Apoio</option>
                                    <option value="instrutor">Instrutor</option>
                                    <option value="palestrante">Palestrante</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="novo-voucher">Voucher (opcional)</label>
                                <input type="text" id="novo-voucher" name="voucher">
                            </div>

                            <button type="submit" class="btn-primary">Cadastrar Participante</button>
                        </form>
                    </div>
                </div>
                
                <div id="ver-participantes" class="tab-content">
                    <h3>Lista de Participantes</h3>
                    <div class="table-container">
                        <div class="form-group">
                            <input type="text" id="busca-participantes" placeholder="Buscar por nome, email ou CPF...">
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Instituição</th>
                                    <th>Tipo</th>
                                    <th>Código</th>
                                    <th>Admin</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participantes as $participante): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($participante['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($participante['email']); ?></td>
                                    <td><?php echo htmlspecialchars($participante['cpf']); ?></td>
                                    <td><?php echo htmlspecialchars($participante['telefone'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($participante['instituicao']); ?></td>
                                    <td>
                                        <span class="badge badge-participante">
                                            <?php echo ucfirst($participante['tipo'] ?? 'participante'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $participante['codigo_barra'] ?? 'N/A'; ?></td>
                                    <td>
                                        <label class="admin-checkbox">
                                            <input type="checkbox" class="admin-toggle" 
                                                data-id="<?php echo $participante['id']; ?>" 
                                                <?php echo $participante['administrador'] ? 'checked' : ''; ?>>
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button class="btn-primary btn-small" onclick="editarParticipante(<?php echo $participante['id']; ?>)">Editar</button>
                                        <button class="btn-primary btn-small" onclick="gerarCracha(<?php echo $participante['id']; ?>, '<?php echo $participante['tipo'] ?? 'participante'; ?>')">Crachá</button>
                                        <button class="btn-primary btn-small" onclick="gerarCertificado(<?php echo $participante['id']; ?>)">Certificado</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal de Edição de Participante -->
                <div class="modal" id="editar-participante-modal">
                    <div class="modal-content">
                        <button class="close-modal" onclick="fecharModal('editar-participante-modal')">&times;</button>
                        <h3>Editar Participante</h3>
                        <form id="editar-participante-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action" value="editar_participante">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="form-group">
                                <label for="edit-nome">Nome</label>
                                <input type="text" id="edit-nome" name="nome" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-email">E-mail</label>
                                <input type="email" id="edit-email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-cpf">CPF</label>
                                <input type="text" id="edit-cpf" name="cpf" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-telefone">Telefone</label>
                                <input type="text" id="edit-telefone" name="telefone">
                            </div>
                            <div class="form-group">
                                <label for="edit-instituicao">Instituição</label>
                                <input type="text" id="edit-instituicao" name="instituicao">
                            </div>
                            <div class="form-group">
                                <label for="edit-tipo">Tipo</label>
                                <select id="edit-tipo" name="tipo" required>
                                    <option value="participante">Participante</option>
                                    <option value="organizacao">Organização</option>
                                    <option value="apoio">Apoio</option>
                                    <option value="instrutor">Instrutor</option>
                                    <option value="palestrante">Palestrante</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-voucher">Voucher</label>
                                <input type="text" id="edit-voucher" name="voucher">
                            </div>
                            <div class="form-group">
                                <label class="admin-checkbox">
                                    <input type="checkbox" id="edit-isento" name="isento_pagamento" value="1">
                                    <span class="checkmark"></span>
                                    Isento de pagamento
                                </label>
                            </div>
                            <button type="submit" class="btn-primary">Salvar Alterações</button>
                            <button type="button" onclick="fecharModal('editar-participante-modal')">Cancelar</button>
                        </form>
                    </div>
                </div>
            </section>
            
            <!-- Seção de Atividades -->
            <section id="atividades-section" class="admin-section">
                <h2>Gerenciar Atividades</h2>
                
                <div class="tabs">
                    <button class="tab-link active" data-tab="cadastrar-atividade">Cadastrar</button>
                    <button class="tab-link" data-tab="ver-atividades">Ver Atividades</button>
                </div>
                
                <div id="cadastrar-atividade" class="tab-content active">
                    <div class="table-container">
                        <h3>Cadastrar Nova Atividade</h3>
                        <form id="cadastrar-atividade-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action" value="cadastrar_atividade">
                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select id="tipo" name="tipo" required>
                                	<option value=""></option>
                                	<option value="credenciamento">Recepção e Credenciamento</option>
                                    <option value="palestra">Palestra</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="oficina">Oficina</option>
                                    <option value="mesa_redonda">Mesa Redonda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="palestrante">Palestrante/Orientador</label>
                                <input type="text" id="palestrante" name="palestrante" required>
                            </div>
                            <div class="form-group">
                                <label for="local">Local</label>
                                <input type="text" id="local" name="local" required>
                            </div>
                            <div class="form-group">
                                <label for="data">Data</label>
                                <div class="input-with-icon">
                                    <input type="text" id="data" name="data" placeholder="dd/mm/aaaa" required readonly>
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hora_inicio">Hora Início</label>
                                <div class="input-with-icon">
                                    <input type="text" id="hora_inicio" name="hora_inicio" required readonly>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="hora_fim">Hora Fim</label>
                                <div class="input-with-icon">
                                    <input type="text" id="hora_fim" name="hora_fim" required readonly>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vagas">Vagas</label>
                                <input type="number" id="vagas" name="vagas" min="1" required>
                            </div>
                            <button type="submit" class="btn-primary">Cadastrar Atividade</button>
                        </form>
                    </div>
                </div>
                                
                <div id="ver-atividades" class="tab-content">
                    <div class="table-container">
                        <h3>Atividades Cadastradas</h3>
                        <div class="form-group">
                            <input type="text" id="busca-atividades" placeholder="Buscar por título, palestrante ou tipo...">
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Palestrante</th>
                                    <th>Local</th>
                                    <th>Data</th>
                                    <th>Horário</th>
                                    <th>Vagas</th>
                                    <th>Inscritos</th>
                                    <th>Confirmada</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atividades as $atividade): ?>
                                <tr class="<?php echo $atividade['ativa'] == '1' ? '' : 'nao-confirmada'; ?>">
                                    <td><?php echo htmlspecialchars($atividade['titulo']); ?></td>
                                    <td><?php echo ucfirst($atividade['tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($atividade['palestrante']); ?></td>
                                    <td><?php echo htmlspecialchars($atividade['sala']); ?></td>
                                    <td><?php echo formatarData($atividade['data']); ?></td>
                                    <td><?php echo $atividade['horario']; ?></td>
                                    <td><?php echo $atividade['vagas']; ?></td>
                                    <td>
                                        <?php 
                                        // Contar inscritos
                                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM inscricoes_atividades WHERE atividade_id = :id");
                                        $stmt->execute([':id' => $atividade['id']]);
                                        $inscritos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                        echo $inscritos;
                                        ?>
                                    </td>
                                    <td>
                                        <label class="admin-checkbox">
                                            <input type="checkbox" class="confirmada-toggle" 
                                                data-id="<?php echo $atividade['id']; ?>" 
                                                <?php echo $atividade['ativa'] == '1' ? 'checked' : ''; ?>>
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <button class="btn-primary btn-small" onclick="editarAtividade(<?php echo $atividade['id']; ?>)">Editar</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal de Edição de Atividade -->
                <div class="modal" id="editar-atividade-modal">
                    <div class="modal-content">
                        <button class="close-modal" onclick="fecharModal('editar-atividade-modal')">&times;</button>
                        <h3>Editar Atividade</h3>
                        <form id="editar-atividade-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action" value="editar_atividade');
                            <input type="hidden" id="edit-atividade-id" name="id">
                            <div class="form-group">
                                <label for="edit-titulo">Título</label>
                                <input type="text" id="edit-titulo" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-tipo">Tipo</label>
                                <select id="edit-tipo" name="tipo" required>
                                    <option value="credenciamento">Recepção e Credenciamento</option>
                                    <option value="palestra">Palestra</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="oficina">Oficina</option>
                                    <option value="mesa_redonda">Mesa Redonda</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-palestrante">Palestrante/Orientador</label>
                                <input type="text" id="edit-palestrante" name="palestrante" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-local">Local</label>
                                <input type="text" id="edit-local" name="local" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-data">Data</label>
                                <div class="input-with-icon">
                                    <input type="text" id="edit-data" name="data" placeholder="dd/mm/aaaa" required readonly>
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-hora_inicio">Hora Início</label>
                                <div class="input-with-icon">
                                    <input type="text" id="edit-hora_inicio" name="hora_inicio" required readonly>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-hora_fim">Hora Fim</label>
                                <div class="input-with-icon">
                                    <input type="text" id="edit-hora_fim" name="hora_fim" required readonly>
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit-vagas">Vagas</label>
                                <input type="number" id="edit-vagas" name="vagas" min="1" required>
                            </div>
                            <button type="submit" class="btn-primary">Salvar Alterações</button>
                            <button type="button" onclick="fecharModal('editar-atividade-modal')">Cancelar</button>
                        </form>
                    </div>
                </div>
            </section>
            
            <!-- Seção de Presenças -->
            <section id="presencas-section" class="admin-section">
                <h2>Registrar Presenças</h2>
                
                <div class="tabs">
                    <button class="tab-link active" data-tab="registrar-presenca">Registrar</button>
                    <button class="tab-link" data-tab="ver-presencas">Ver Presenças</button>
                </div>
                
                <div id="registrar-presenca" class="tab-content active">
                    <div class="table-container">
                        <form id="registrar-presenca-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="action" value="registrar_presenca">
                            <div class="form-group">
                                <label for="atividade">Atividade</label>
                                <select id="atividade" name="atividade_id" required>
                                    <option value="">Selecione uma atividade</option>
                                    <?php foreach ($atividades as $atividade): ?>
                                    <option value="<?php echo $atividade['id']; ?>">
                                        <?php echo htmlspecialchars($atividade['titulo']) . ' - ' . formatarData($atividade['data']) . ' - ' . $atividade['hora_inicio']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="codigo_barras">Código de Barras</label>
                                <input type="text" id="codigo_barras" name="codigo_barras" required autofocus>
                            </div>
                            <button type="submit" class="btn-primary">Registrar Presença</button>
                        </form>
                    </div>
                </div>
                
                <div id="ver-presencas" class="tab-content">
                    <h3>Presenças Registradas</h3>
                    <div class="table-container">
                        <div class="form-group">
                            <input type="text" id="busca-presencas" placeholder="Buscar por participante ou atividade...">
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Participante</th>
                                    <th>Atividade</th>
                                    <th>Data</th>
                                    <th>Presença</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($presencas as $presenca): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($presenca['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($presenca['titulo']); ?></td>
                                    <td><?php echo formatarDataHora($presenca['data_hora']); ?></td>
                                    <td><span class="status-approved">Confirmada</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            
            <!-- Seção de Comprovantes -->
            <section id="comprovantes-section" class="admin-section">
                <h2>Validação de Comprovantes</h2>
                
                <div class="table-container">
                    <div class="form-group">
                        <input type="text" id="busca-comprovantes" placeholder="Buscar por participante...">
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Participante</th>
                                <th>Data Envio</th>
                                <th>Status</th>
                                <th>Comprovante</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comprovantes as $comprovante): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($comprovante['participante_nome']); ?></td>
                                <td><?php echo formatarDataHora($comprovante['data_envio']); ?></td>
                                <td>
                                    <?php if ($comprovante['status'] == 'aprovado'): ?>
                                        <span class="status-approved">Aprovado</span>
                                    <?php elseif ($comprovante['status'] == 'rejeitado'): ?>
                                        <span class="status-rejected">Rejeitado</span>
                                    <?php else: ?>
                                        <span class="status-pending">Pendente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn-primary btn-small" onclick="verComprovante(<?php echo $comprovante['id']; ?>)">Visualizar</button>
                                </td>
                                <td>
                                    <?php if ($comprovante['status'] == 'pendente'): ?>
                                        <button class="btn-primary btn-small" onclick="validarPagamento(<?php echo $comprovante['id']; ?>, true)">Aprovar</button>
                                        <button class="btn-primary btn-small" style="background: linear-gradient(45deg, var(--error-color), #ff6b6b);" onclick="validarPagamento(<?php echo $comprovante['id']; ?>, false)">Rejeitar</button>
                                    <?php else: ?>
                                        <span>Processado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Modal para visualização de comprovante -->
                <div class="modal" id="comprovante-modal">
                    <div class="modal-content">
                        <button class="close-modal" onclick="fecharModal('comprovante-modal')">&times;</button>
                        <h3>Comprovante de Pagamento</h3>
                        <div id="comprovante-content">
                            <p>Carregando comprovante...</p>
                        </div>
                        <button onclick="fecharModal('comprovante-modal')">Fechar</button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="imagens/logo.jpg" alt="Logo Tech Week" class="footer-logo-dark">
                    <img src="imagens/logo-light.jpg" alt="Logo Tech Week" class="footer-logo-light">
                </div>
                <div class="footer-info">
                    <h3>1ª TechWeek</h3>
                    <p>Organização: Curso de Análise e Desenvolvimento de Sistemas</p>
                    <p>Contato: techweek@example.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 1ª TechWeek - Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <!-- JsBarcode para gerar códigos de barras -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <!-- InputMask para máscaras -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script>
        // Inicializar Flatpickr para campos de data e hora
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração para o formulário de cadastro
            flatpickr("#data", {
                dateFormat: "d/m/Y",
                locale: "pt",
                allowInput: false,
                clickOpens: true
            });
            
            flatpickr("#hora_inicio", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                locale: "pt",
                allowInput: false,
                clickOpens: true,
                minuteIncrement: 1
            });
            
            flatpickr("#hora_fim", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                locale: "pt",
                allowInput: false,
                clickOpens: true,
                minuteIncrement: 1
            });
            
            // Máscaras para CPF e telefone
            $('#novo-cpf, #edit-cpf').inputmask('999.999.999-99');
            $('#novo-telefone, #edit-telefone').inputmask('(99) 99999-9999');
            
            // Inicializar tabs
            document.querySelectorAll('.tab-link').forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');
                    
                    // Desativar todas as tabs
                    document.querySelectorAll('.tab-link').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                    
                    // Ativar tab clicada
                    tab.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Busca em tempo real
            document.getElementById('busca-participantes').addEventListener('input', function() {
                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('#ver-participantes .data-table tbody tr');
                
                linhas.forEach(linha => {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(termo) ? '' : 'none';
                });
            });
            
            document.getElementById('busca-atividades').addEventListener('input', function() {
                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('#ver-atividades .data-table tbody tr');
                
                linhas.forEach(linha => {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(termo) ? '' : 'none';
                });
            });
            
            document.getElementById('busca-presencas').addEventListener('input', function() {
                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('#ver-presencas .data-table tbody tr');
                
                linhas.forEach(linha => {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(termo) ? '' : 'none';
                });
            });
            
            document.getElementById('busca-comprovantes').addEventListener('input', function() {
                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('#comprovantes-section .data-table tbody tr');
                
                linhas.forEach(linha => {
                    const textoLinha = linha.textContent.toLowerCase();
                    linha.style.display = textoLinha.includes(termo) ? '' : 'none';
                });
            });
        });
        
        // Função para exibir mensagens inline
        function exibirMensagem(mensagem, tipo) {
            const divMensagem = document.getElementById('mensagem');
            divMensagem.innerHTML = `<div class="message ${tipo}">${mensagem}</div>`;
            setTimeout(() => {
                divMensagem.innerHTML = '';
            }, 5000);
        }
        
        // Navegação do painel de admin com histórico
        document.querySelectorAll('.admin-nav').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                
                // Ativar link
                document.querySelectorAll('.admin-nav').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Mostrar seção correspondente
                document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
                document.getElementById(section + '-section').classList.add('active');
                
                // Atualizar a URL e o histórico
                history.pushState({section: section}, '', `#${section}`);
                
                // Salvar a seção atual no localStorage
                localStorage.setItem('currentSection', section);
                
                // Fechar menu mobile se estiver aberto
                menu.classList.remove('active');
            });
        });
        
        // Ao carregar a página, verificar se há uma seção salva
        window.addEventListener('load', function() {
            const savedSection = localStorage.getItem('currentSection');
            if (savedSection) {
                // Ativar link
                document.querySelectorAll('.admin-nav').forEach(l => l.classList.remove('active'));
                const activeLink = document.querySelector(`.admin-nav[data-section="${savedSection}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
                
                // Mostrar seção correspondente
                document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
                const activeSection = document.getElementById(savedSection + '-section');
                if (activeSection) {
                    activeSection.classList.add('active');
                }
            }
        });
        
        // Lidar com o botão voltar/avançar do navegador
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.section) {
                const section = event.state.section;
                
                // Ativar link
                document.querySelectorAll('.admin-nav').forEach(l => l.classList.remove('active'));
                const activeLink = document.querySelector(`.admin-nav[data-section="${section}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
                
                // Mostrar seção correspondente
                document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
                const activeSection = document.getElementById(section + '-section');
                if (activeSection) {
                    activeSection.classList.add('active');
                }
                
                // Salvar a seção atual no localStorage
                localStorage.setItem('currentSection', section);
            }
        });
        
        // Menu mobile
        const menuToggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.menu');
        
        menuToggle.addEventListener('click', () => {
            menu.classList.toggle('active');
        });
        
        // User dropdown
        const userBtn = document.querySelector('.user-btn');
        const userDropdown = document.getElementById('userDropdown');
        
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
        
        // Sistema de tema claro/escuro
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = themeToggle.querySelector('i');
        const body = document.body;
        const logoDark = document.querySelector('.logo-dark');
        const logoLight = document.querySelector('.logo-light');
        const footerLogoDark = document.querySelector('.footer-logo-dark');
        const footerLogoLight = document.querySelector('.footer-logo-light');
        
        // Verificar preferência salva no localStorage
        if (localStorage.getItem('theme') === 'light') {
            body.classList.add('light-theme');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
            if (logoDark) logoDark.style.display = 'none';
            if (logoLight) logoLight.style.display = 'block';
            if (footerLogoDark) footerLogoDark.style.display = 'none';
            if (footerLogoLight) footerLogoLight.style.display = 'block';
        } else {
            if (logoDark) logoDark.style.display = 'block';
            if (logoLight) logoLight.style.display = 'none';
            if (footerLogoDark) footerLogoDark.style.display = 'block';
            if (footerLogoLight) footerLogoLight.style.display = 'none';
        }
        
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('light-theme');
            
            if (body.classList.contains('light-theme')) {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                if (logoDark) logoDark.style.display = 'none';
                if (logoLight) logoLight.style.display = 'block';
                if (footerLogoDark) footerLogoDark.style.display = 'none';
                if (footerLogoLight) footerLogoLight.style.display = 'block';
            } else {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                if (logoDark) logoDark.style.display = 'block';
                if (logoLight) logoLight.style.display = 'none';
                if (footerLogoDark) footerLogoDark.style.display = 'block';
                if (footerLogoLight) footerLogoLight.style.display = 'none';
            }
        });
        
        // Fechar modais com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                fecharModal('editar-participante-modal');
                fecharModal('editar-atividade-modal');
                fecharModal('comprovante-modal');
            }
        });
        
        // Função para fechar modal com verificação de alterações
        function fecharModal(id) {
            const modal = document.getElementById(id);
            const form = modal.querySelector('form');
            let hasChanges = false;
            
            // Verificar se há alterações não salvas
            if (form) {
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    if (input.defaultValue !== input.value) {
                        hasChanges = true;
                    }
                });
            }
            
            if (hasChanges && !confirm('Há alterações não salvas. Deseja realmente sair?')) {
                return;
            }
            
            modal.style.display = 'none';
        }
        
        // Função para abrir modal
        function abrirModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        
        // Cadastrar participante
        document.getElementById('cadastrar-participante-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verificar se as senhas coincidem
            const senha = document.getElementById('novo-senha').value;
            const repitaSenha = document.getElementById('novo-repita_senha').value;
            
            if (senha !== repitaSenha) {
                exibirMensagem('As senhas não coincidem!', 'erro');
                return;
            }
            
            const formData = new FormData(this);
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirMensagem('Participante cadastrado com sucesso!', 'sucesso');
                    this.reset();
                    // Recarregar a lista de participantes
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao cadastrar participante.', 'erro');
            });
        });
        
        // Editar participante
        function editarParticipante(id) {
            // Buscar dados do participante via AJAX
            const formData = new FormData();
            formData.append('action', 'buscar_participante');
            formData.append('id', id);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit-id').value = data.participante.id;
                    document.getElementById('edit-nome').value = data.participante.nome;
                    document.getElementById('edit-nome').defaultValue = data.participante.nome;
                    document.getElementById('edit-email').value = data.participante.email;
                    document.getElementById('edit-email').defaultValue = data.participante.email;
                    document.getElementById('edit-cpf').value = data.participante.cpf;
                    document.getElementById('edit-cpf').defaultValue = data.participante.cpf;
                    document.getElementById('edit-telefone').value = data.participante.telefone || '';
                    document.getElementById('edit-telefone').defaultValue = data.participante.telefone || '';
                    document.getElementById('edit-instituicao').value = data.participante.instituicao || '';
                    document.getElementById('edit-instituicao').defaultValue = data.participante.instituicao || '';
                    document.getElementById('edit-tipo').value = data.participante.tipo || 'participante';
                    document.getElementById('edit-tipo').defaultValue = data.participante.tipo || 'participante';
                    document.getElementById('edit-voucher').value = data.participante.voucher || '';
                    document.getElementById('edit-voucher').defaultValue = data.participante.voucher || '';
                    document.getElementById('edit-isento').checked = data.participante.isento_pagamento == 1;
                    document.getElementById('edit-isento').defaultChecked = data.participante.isento_pagamento == 1;
                    
                    abrirModal('editar-participante-modal');
                } else {
                    exibirMensagem('Erro ao carregar dados: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao carregar dados do participante.', 'erro');
            });
        }
        
        // Formulário de edição de participante
        document.getElementById('editar-participante-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirMensagem('Dados atualizados com sucesso!', 'sucesso');
                    fecharModal('editar-participante-modal');
                    // Recarregar a página para atualizar a lista
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao atualizar participante.', 'erro');
            });
        });
        
        // Toggle administrador
        document.querySelectorAll('.admin-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                var isAdmin = this.checked;
                
                if(isAdmin) isAdmin=1;
                else if(!isAdmin) isAdmin=0;
                
                const formData = new FormData();
                formData.append('action', 'toggle_admin');
                formData.append('id', id);
                formData.append('administrador', isAdmin);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
                
                fetch('painel_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        exibirMensagem('Erro: ' + data.message, 'erro');
                        this.checked = !isAdmin;
                    } else {
                        exibirMensagem('Status de administrador atualizado', 'sucesso');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    this.checked = !isAdmin;
                    exibirMensagem('Erro ao atualizar status.', 'erro');
                });
            });
        });
        
        // Toggle atividade confirmada
        document.querySelectorAll('.confirmada-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const ativa = this.checked ? '1' : '0';
                
                const formData = new FormData();
                formData.append('action', 'toggle_ativa');
                formData.append('id', id);
                formData.append('ativa', ativa);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
                
                fetch('painel_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        exibirMensagem('Erro: ' + data.message, 'erro');
                        this.checked = !this.checked;
                    } else {
                        // Atualizar a cor da linha
                        const linha = this.closest('tr');
                        if (ativa === '1') {
                            linha.classList.remove('nao-confirmada');
                        } else {
                            linha.classList.add('nao-confirmada');
                        }
                        exibirMensagem('Status da atividade atualizado', 'sucesso');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    this.checked = !this.checked;
                    exibirMensagem('Erro ao atualizar status.', 'erro');
                });
            });
        });
        
        // Cadastrar atividade
        document.getElementById('cadastrar-atividade-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Converter a data do formato dd/mm/yyyy para yyyy-mm-dd
            const dataInput = document.getElementById('data');
            const dataParts = dataInput.value.split('/');
            if (dataParts.length === 3) {
                dataInput.value = `${dataParts[2]}-${dataParts[1]}-${dataParts[0]}`;
            }
            
            const formData = new FormData(this);
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirMensagem('Atividade cadastrada com sucesso!', 'sucesso');
                    this.reset();
                    // Recarregar a página para atualizar a lista
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao cadastrar atividade.', 'erro');
            });
        });
        
        // Editar atividade
        function editarAtividade(id) {
            // Buscar dados da atividade via AJAX
            const formData = new FormData();
            formData.append('action', 'buscar_atividade');
            formData.append('id', id);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit-atividade-id').value = data.atividade.id;
                    document.getElementById('edit-titulo').value = data.atividade.titulo;
                    document.getElementById('edit-titulo').defaultValue = data.atividade.titulo;
                    document.getElementById('edit-tipo').value = data.atividade.tipo;
                    document.getElementById('edit-tipo').defaultValue = data.atividade.tipo;
                    document.getElementById('edit-palestrante').value = data.atividade.palestrante;
                    document.getElementById('edit-palestrante').defaultValue = data.atividade.palestrante;
                    document.getElementById('edit-local').value = data.atividade.sala;
                    document.getElementById('edit-local').defaultValue = data.atividade.sala;
                    
                    // Formatar data de yyyy-mm-dd para dd/mm/yyyy
                    const dataParts = data.atividade.data.split('-');
                    if (dataParts.length === 3) {
                        document.getElementById('edit-data').value = `${dataParts[2]}/${dataParts[1]}/${dataParts[0]}`;
                        document.getElementById('edit-data').defaultValue = `${dataParts[2]}/${dataParts[1]}/${dataParts[0]}`;
                    } else {
                        document.getElementById('edit-data').value = data.atividade.data;
                        document.getElementById('edit-data').defaultValue = data.atividade.data;
                    }
                    
                    document.getElementById('edit-hora_inicio').value = data.atividade.hora_inicio;
                    document.getElementById('edit-hora_inicio').defaultValue = data.atividade.hora_inicio;
                    document.getElementById('edit-hora_fim').value = data.atividade.hora_fim;
                    document.getElementById('edit-hora_fim').defaultValue = data.atividade.hora_fim;
                    document.getElementById('edit-vagas').value = data.atividade.vagas;
                    document.getElementById('edit-vagas').defaultValue = data.atividade.vagas;
                    
                    // Inicializar Flatpickr para os campos de edição
                    flatpickr("#edit-data", {
                        dateFormat: "d/m/Y",
                        locale: "pt",
                        allowInput: false,
                        clickOpens: true,
                        defaultDate: document.getElementById('edit-data').value
                    });
                    
                    flatpickr("#edit-hora_inicio", {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        locale: "pt",
                        allowInput: false,
                        clickOpens: true,
                        minuteIncrement: 1,
                        defaultDate: document.getElementById('edit-hora_inicio').value
                    });
                    
                    flatpickr("#edit-hora_fim", {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        locale: "pt",
                        allowInput: false,
                        clickOpens: true,
                        minuteIncrement: 1,
                        defaultDate: document.getElementById('edit-hora_fim').value
                    });
                    
                    abrirModal('editar-atividade-modal');
                } else {
                    exibirMensagem('Erro ao carregar dados: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao carregar dados da atividade.', 'erro');
            });
        }
        
        // Formulário de edição de atividade
        document.getElementById('editar-atividade-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Converter a data do formato dd/mm/yyyy para yyyy-mm-dd
            const dataInput = document.getElementById('edit-data');
            const dataParts = dataInput.value.split('/');
            if (dataParts.length === 3) {
                dataInput.value = `${dataParts[2]}-${dataParts[1]}-${dataParts[0]}`;
            }
            
            const formData = new FormData(this);
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirMensagem('Atividade atualizada com sucesso!', 'sucesso');
                    fecharModal('editar-atividade-modal');
                    // Recarregar a página para atualizar a lista
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao atualizar atividade.', 'erro');
            });
        });
        
        // Registrar presença
        document.getElementById('registrar-presenca-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    exibirMensagem('Presença registrada com sucesso!', 'sucesso');
                    this.reset();
                    document.getElementById('codigo_barras').focus();
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao registrar presença.', 'erro');
            });
        });
        
        // Validar pagamento via AJAX
        function validarPagamento(id, aprovado) {
            if (!confirm(`Tem certeza que deseja ${aprovado ? 'aprovar' : 'reprovar'} este comprovante?`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'validar_pagamento');
            formData.append('id', id);
            formData.append('aprovado', aprovado);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar a linha da tabela sem recarregar
                    const linha = document.querySelector(`tr:has(button[onclick="validarPagamento(${id}, ${aprovado})"])`).parentNode.parentNode;
                    linha.cells[2].innerHTML = aprovado ? '<span class="status-approved">Aprovado</span>' : '<span class="status-rejected">Rejeitado</span>';
                    linha.cells[4].innerHTML = '<span>Processado</span>';
                    
                    exibirMensagem('Validação atualizada com sucesso!', 'sucesso');
                } else {
                    exibirMensagem('Erro: ' + data.message, 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao validar pagamento.', 'erro');
            });
        }
        
        // Ver comprovante
        function verComprovante(id) {
            // Abrir modal com o comprovante
            document.getElementById('comprovante-content').innerHTML = '<p>Carregando comprovante...</p>';
            abrirModal('comprovante-modal');
            
            // Buscar comprovante via AJAX
            const formData = new FormData();
            formData.append('action', 'visualizar_comprovante');
            formData.append('id', id);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.tipo_arquivo === 'pdf') {
                        document.getElementById('comprovante-content').innerHTML = `
                            <iframe src="${data.arquivo}" width="100%" height="500px" style="border: none;"></iframe>
                        `;
                    } else {
                        document.getElementById('comprovante-content').innerHTML = `
                            <img src="${data.arquivo}" alt="Comprovante" style="max-width: 100%;">
                        `;
                    }
                } else {
                    document.getElementById('comprovante-content').innerHTML = `<p>Erro: ${data.message}</p>`;
                }
            })
            .catch(error => {
                document.getElementById('comprovante-content').innerHTML = `<p>Erro ao carregar comprovante: ${error}</p>`;
            });
        }
        
        // Gerar crachá
        function gerarCracha(id, tipo) {
            // Buscar dados do participante
            const formData = new FormData();
            formData.append('action', 'buscar_participante');
            formData.append('id', id);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('painel_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const codigoBarra = data.participante.codigo_barra;
                    
                    // Criar um canvas para gerar o código de barras
                    const canvas = document.createElement('canvas');
                    JsBarcode(canvas, codigoBarra, {
                        format: "CODE128",
                        displayValue: true,
                        fontSize: 16,
                        background: "#ffffff",
                        lineColor: "#000000"
                    });
                    
                    // Converter canvas para data URL
                    const barcodeDataURL = canvas.toDataURL('image/png');
                    
                    abrirJanelaCracha(data.participante, tipo, barcodeDataURL);
                } else {
                    exibirMensagem('Erro ao carregar dados do participante', 'erro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                exibirMensagem('Erro ao carregar dados do participante.', 'erro');
            });
        }

        // Função auxiliar para abrir a janela do crachá
        function abrirJanelaCracha(participante, tipo, imagemBarcode) {
            // Definir cores para cada tipo
            const cores = {
                'participante': '#00BF63',
                'organizacao': '#FF6B00',
                'apoio': '#0066CC',
                'instrutor': '#9900CC',
                'palestrante': '#CC0000'
            };
            
            const cor = cores[tipo] || '#00BF63';
            
            // Abrir em uma nova janela com o crachá
            const crachaWindow = window.open('', '_blank', 'width=400,height=500');
            
            let barcodeHTML = '';
            if (imagemBarcode) {
                barcodeHTML = `
                    <div style="text-align: center; margin: 15px 0;">
                        <img src="${imagemBarcode}" alt="Código de Barras" style="max-width: 100%; height: auto; border: 1px solid #ddd;">
                    </div>
                `;
            } else {
                barcodeHTML = `
                    <div class="cracha-codigo" style="font-family: monospace; font-size: 16px; letter-spacing: 2px; background: #f0f0f0; padding: 10px; border-radius: 5px; margin: 10px 0;">
                        ${participante.codigo_barra || 'N/A'}
                    </div>
                `;
            }
            
            crachaWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Crachá - 1ª TechWeek</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            background: white;
                            color: black;
                            text-align: center;
                        }
                        .cracha-container {
                            border: 2px solid ${cor};
                            border-radius: 10px;
                            padding: 20px;
                            max-width: 300px;
                            margin: 0 auto;
                            background: white;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                            position: relative;
                            overflow: hidden;
                        }
                        .cracha-sidebar {
                            position: absolute;
                            left: 0;
                            top: 0;
                            height: 100%;
                            width: 10px;
                            background-color: ${cor};
                        }
                        .cracha-header {
                            margin-bottom: 20px;
                        }
                        .cracha-header h2 {
                            color: ${cor};
                            margin: 0;
                        }
                        .cracha-nome {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 10px;
                        }
                        .cracha-tipo {
                            font-size: 14px;
                            margin-bottom: 15px;
                            color: #666;
                            font-weight: bold;
                            text-transform: uppercase;
                        }
                        .cracha-footer {
                            margin-top: 20px;
                            font-size: 12px;
                            color: #999;
                        }
                    </style>
                </head>
                <body>
                    <div class="cracha-container">
                        <div class="cracha-sidebar"></div>
                        <div class="cracha-header">
                            <h2>1ª TechWeek</h2>
                        </div>
                        <div class="cracha-nome">${participante.nome}</div>
                        <div class="cracha-tipo">${tipo.toUpperCase()}</div>
                        ${barcodeHTML}
                        <div class="cracha-footer">
                            Evento: 28 a 31 de Agosto de 2025
                        </div>
                    </div>
                </body>
                </html>
            `);
            
            crachaWindow.document.close();
        }
        
        // Gerar certificado
        function gerarCertificado(id, atividade_id = null) {
            let url = `gerar_certificado.php?id=${id}`;
            if (atividade_id) {
                url += `&atividade_id=${atividade_id}`;
            }
            window.open(url, '_blank');
        }
        
        // Logout
        document.getElementById('logout-btn').addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'logout.php';
            }
        });
    </script>
</body>
</html>
