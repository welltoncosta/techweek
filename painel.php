<?php
session_start();

$_SESSION["administrador"]=1;
if(!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("location: index.php#login");
    exit;
}

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'techweek';
$username = 'wellton';
$password = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Recuperar dados do usuário
$usuario = $_SESSION['usuario'];

// Verificar se é administrador
$isAdmin = true;

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_senha'])) {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
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
            
            $mensagem_senha = "Senha alterada com sucesso!";
            $tipo_mensagem = "sucesso";
        } else {
            $mensagem_senha = "As novas senhas não coincidem!";
            $tipo_mensagem = "erro";
        }
    } else {
        $mensagem_senha = "Senha atual incorreta!";
        $tipo_mensagem = "erro";
    }
}

// Processar registro de presenças (apenas para admin)
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_presenca'])) {
    $atividade = $_POST['atividade'];
    $raBusca = ltrim($_POST['raBusca'], '0');
    $raBusca = substr($raBusca, 0, -1);
    
    // Verificar se o participante existe
    $stmt = $pdo->prepare("SELECT id FROM participantes WHERE ra = :ra");
    $stmt->execute([':ra' => $raBusca]);
    $participante = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($participante) {
        $id_participante = $participante['id'];
        
        // Verificar se já está registrado
        $stmt = $pdo->prepare("SELECT * FROM presencas WHERE id_participante = :id_participante AND id_atividade = :id_atividade");
        $stmt->execute([':id_participante' => $id_participante, ':id_atividade' => $atividade]);
        
        if ($stmt->rowCount() > 0) {
            $mensagem_presenca = "Participante já registrado para esta atividade!";
            $tipo_mensagem_presenca = "erro";
        } else {
            // Registrar presença
            $data_hora = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO presencas (id_participante, id_atividade, data_hora) VALUES (:id_participante, :id_atividade, :data_hora)");
            $stmt->execute([
                ':id_participante' => $id_participante,
                ':id_atividade' => $atividade,
                ':data_hora' => $data_hora
            ]);
            
            $mensagem_presenca = "Presença registrada com sucesso!";
            $tipo_mensagem_presenca = "sucesso";
        }
    } else {
        $mensagem_presenca = "Participante não encontrado!";
        $tipo_mensagem_presenca = "erro";
    }
}

// Processar cadastro de atividades (apenas para admin)
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_atividade'])) {
    $titulo = $_POST['titulo'];
    $palestrante = $_POST['palestrante'];
    $local = $_POST['local'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    
    $stmt = $pdo->prepare("INSERT INTO atividades (titulo, palestrante, local, data, horario) VALUES (:titulo, :palestrante, :local, :data, :horario)");
    $stmt->execute([
        ':titulo' => $titulo,
        ':palestrante' => $palestrante,
        ':local' => $local,
        ':data' => $data,
        ':horario' => $horario
    ]);
    
    $mensagem_atividade = "Atividade cadastrada com sucesso!";
    $tipo_mensagem_atividade = "sucesso";
}

// Recuperar oficinas do participante
$stmt = $pdo->prepare("SELECT * FROM participantes_oficinas WHERE idParticipante = :id");
$stmt->execute([':id' => $usuario['id']]);
$oficinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recuperar atividades (para admin)
if ($isAdmin) {
    $stmt = $pdo->prepare("SELECT * FROM atividades");
    $stmt->execute();
    $atividades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recuperar inscritos
    $stmt = $pdo->prepare("SELECT * FROM participantes");
    $stmt->execute();
    $inscritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Recuperar presenças
    $stmt = $pdo->prepare("SELECT p.nome, a.titulo, pr.data_hora 
                          FROM presencas pr 
                          JOIN participantes p ON pr.id_participante = p.id 
                          JOIN atividades a ON pr.id_atividade = a.id 
                          ORDER BY pr.data_hora DESC");
    $stmt->execute();
    $presencas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Participante - 1ª TechWeek</title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --erro: #ff3860;
            --sucesso: #09c372;
        }
        
        .light-theme {
            --bg-color: #f0f0f0;
            --text-color: #333333;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: #007a3d;
            --black: #f0f0f0;
            --light-gray: #666666;
            --dark-gray: #e0e0e0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
            position: relative;
            font-size: 16px;
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
                radial-gradient(circle at 10% 20%, rgba(0, 191, 99, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(0, 255, 0, 0.05) 0%, transparent 20%);
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
        
        .logo:hover img {
            filter: drop-shadow(0 0 8px var(--neon-green));
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
            color: var(--neon-green);
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
            color: var(--neon-green);
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
            background-color: rgba(0, 191, 99, 0.2);
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
        
        /* Menu */
        .menu-toggle {
            display: block;
            background: none;
            border: none;
            color: var(--neon-green);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .menu-toggle:hover {
            background-color: rgba(0, 191, 99, 0.2);
        }
        
        .menu {
            display: none;
            flex-direction: column;
            width: 100%;
            list-style: none;
            background-color: var(--black);
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 100;
            padding: 10px 0;
            border-top: 1px solid var(--border-color);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        }
        
        .menu.active {
            display: flex;
        }
        
        .menu li {
            width: 100%;
        }
        
        .menu a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 600;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1.1rem;
        }
        
        .menu a:hover {
            background-color: rgba(0, 191, 99, 0.2);
            color: var(--neon-green);
        }
        
        .menu a.vermelho {
            color: var(--neon-green);
            font-weight: 700;
        }
        
        /* Main Content */
        .main-content {
            padding: 40px 0;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--neon-green);
        }
        
        .welcome-section p {
            font-size: 1.2rem;
            color: var(--light-gray);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }
        
        @media (min-width: 768px) {
            .dashboard-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 992px) {
            .dashboard-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        .dashboard-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 191, 99, 0.1);
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(0, 255, 0, 0.3);
            border-color: var(--neon-green);
        }
        
        .dashboard-card i {
            font-size: 2.5rem;
            color: var(--neon-green);
            margin-bottom: 15px;
        }
        
        .dashboard-card h3 {
            color: var(--text-color);
            margin-bottom: 12px;
            font-size: 1.4rem;
        }
        
        .dashboard-card p {
            color: var(--light-gray);
            margin-bottom: 20px;
        }
        
        .dashboard-card .btn {
            display: inline-block;
            background: linear-gradient(45deg, var(--tech-green), var(--neon-green));
            color: var(--black);
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .dashboard-card .btn:hover {
            background: linear-gradient(45deg, var(--neon-green), var(--tech-green));
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }
        
        /* User Info Section */
        .user-info {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 40px;
            box-shadow: 0 0 15px rgba(0, 191, 99, 0.1);
        }
        
        .user-info h2 {
            color: var(--neon-green);
            margin-bottom: 20px;
            font-size: 1.8rem;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        @media (min-width: 768px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--neon-green);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .info-value {
            color: var(--text-color);
            font-size: 1.1rem;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--neon-green);
            font-weight: 600;
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
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--neon-green), var(--tech-green));
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
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
            color: var(--sucesso);
            border: 1px solid var(--sucesso);
        }
        
        .message.erro {
            background-color: rgba(255, 56, 96, 0.2);
            color: var(--erro);
            border: 1px solid var(--erro);
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
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(217, 217, 217, 0.1);
            color: var(--light-gray);
            font-size: 0.95rem;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: rgba(0, 191, 99, 0.05);
        }
        
        .verde {
            color: var(--neon-green);
        }
        
        .vermelho {
            color: var(--erro);
        }
        
        /* Footer */
        footer {
            background: var(--black);
            padding: 30px 0 15px;
            border-top: 1px solid var(--border-color);
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
        }
        
        .footer-info {
            text-align: center;
        }
        
        .footer-info h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
            font-size: 1.4rem;
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
        
        /* Media Queries para tablets e desktops */
        @media (min-width: 768px) {
            .menu-toggle {
                display: none;
            }
            
            .menu {
                display: flex;
                flex-direction: row;
                position: static;
                width: auto;
                background: transparent;
                padding: 0;
                border: none;
                box-shadow: none;
            }
            
            .menu li {
                width: auto;
            }
            
            .menu a {
                padding: 8px 15px;
                font-size: 0.95rem;
            }
        }
        
        @media (min-width: 992px) {
            .footer-content {
                flex-direction: row;
                gap: 40px;
            }
            
            .footer-logo, .footer-info {
                text-align: left;
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
                    <img src="imagens/logo.jpg" alt="Logo Tech Week">
                    <div class="event-title">
                        <h1>1ª TechWeek</h1>
                        <span>Painel do Participante</span>
                    </div>
                </div>
                
                <ul class="menu">
                    <li><a href="#dashboard">Dashboard</a></li>
                    <li><a href="#dados">Meus Dados</a></li>
                    <li><a href="#oficinas">Minhas Oficinas</a></li>
                    <li><a href="#certificado">Certificado</a></li>
                    <?php if ($isAdmin): ?>
                    <li><a href="#admin-presencas">Presenças</a></li>
                    <li><a href="#admin-inscritos">Inscritos</a></li>
                    <li><a href="#admin-atividades">Atividades</a></li>
                    <?php endif; ?>
                </ul>

                <div class="header-controls">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <div class="user-menu">
                        <button class="user-btn">
                            <i class="fas fa-user"></i>
                            <?php echo explode(' ', $usuario['nome'])[0]; ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#dados"><i class="fas fa-user-circle"></i> Meu Perfil</a>
                            <a href="index.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </div>
                    
                    <button class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <h1>Olá, <?php echo $usuario['nome']; ?>!</h1>
                <p>Bem-vindo(a) ao seu painel de participante da 1ª TechWeek. Aqui você pode gerenciar suas inscrições, verificar suas oficinas e acessar seu certificado.</p>
            </section>
            
            <!-- Dashboard Cards -->
            <section class="dashboard-cards" id="dashboard">
                <div class="dashboard-card">
                    <i class="fas fa-user-circle"></i>
                    <h3>Meus Dados</h3>
                    <p>Visualize e gerencie suas informações pessoais</p>
                    <a href="#dados" class="btn">Acessar</a>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Minhas Oficinas</h3>
                    <p>Consulte as oficinas nas quais você está inscrito(a)</p>
                    <a href="#oficinas" class="btn">Ver Oficinas</a>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-certificate"></i>
                    <h3>Certificado</h3>
                    <p>Acesse e baixe seu certificado de participação</p>
                    <a href="#certificado" class="btn">Obter Certificado</a>
                </div>
            </section>
            
            <!-- User Info Section -->
            <section class="user-info" id="dados">
                <h2>Meus Dados Pessoais</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nome Completo</span>
                        <span class="info-value"><?php echo htmlspecialchars($usuario['nome']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">E-mail</span>
                        <span class="info-value"><?php echo htmlspecialchars($usuario['email']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">CPF</span>
                        <span class="info-value"><?php echo htmlspecialchars($usuario['cpf']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Telefone</span>
                        <span class="info-value"><?php echo !empty($usuario['telefone']) ? htmlspecialchars($usuario['telefone']) : 'Não informado'; ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Instituição</span>
                        <span class="info-value"><?php echo htmlspecialchars($usuario['instituicao']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Data de Inscrição</span>
                        <span class="info-value"><?php echo date('d/m/Y'); ?></span>
                    </div>
                </div>
                
                <!-- Alterar Senha -->
                <h3 style="margin-top: 30px; color: var(--neon-green);">Alterar Senha</h3>
                <?php if (isset($mensagem_senha)): ?>
                <div class="message <?php echo $tipo_mensagem; ?>">
                    <?php echo $mensagem_senha; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                    
                    <button type="submit" name="alterar_senha" class="btn-primary">Alterar Senha</button>
                </form>
            </section>
            
            <!-- Oficinas Section -->
            <section id="oficinas">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Minhas Oficinas</h2>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Oficina</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Local</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($oficinas)): ?>
                                <?php foreach ($oficinas as $oficina): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($oficina['nome']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($oficina['data'])); ?></td>
                                        <td><?php echo htmlspecialchars($oficina['horario']); ?></td>
                                        <td><?php echo htmlspecialchars($oficina['local']); ?></td>
                                        <td>
                                            <span style="color: var(--neon-green);">Inscrito</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Você ainda não se inscreveu em nenhuma oficina.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Certificado Section -->
            <section id="certificado" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Certificado de Participação</h2>
                
                <div class="dashboard-card">
                    <i class="fas fa-certificate" style="font-size: 3rem;"></i>
                    <h3>Certificado da 1ª TechWeek</h3>
                    <p>Seu certificado de participação estará disponível para download após o término do evento.</p>
                    <button class="btn" style="opacity: 0.7; cursor: not-allowed;">Disponível em Breve</button>
                </div>
            </section>
            
            <!-- Seções Administrativas -->
            <?php if ($isAdmin): ?>
            
            <!-- Registrar Presenças -->
            <section id="admin-presencas" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Registrar Presenças</h2>
                
                <?php if (isset($mensagem_presenca)): ?>
                <div class="message <?php echo $tipo_mensagem_presenca; ?>">
                    <?php echo $mensagem_presenca; ?>
                </div>
                <?php endif; ?>
                
                <div class="table-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="atividade">Atividade</label>
                            <select id="atividade" name="atividade" required>
                                <option value="">Selecione uma atividade</option>
                                <?php foreach ($atividades as $atividade): ?>
                                <option value="<?php echo $atividade['id']; ?>">
                                    <?php echo htmlspecialchars($atividade['titulo']); ?> - 
                                    <?php echo date('d/m/Y', strtotime($atividade['data'])); ?> - 
                                    <?php echo $atividade['horario']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="raBusca">Código de Barras (RA)</label>
                            <input type="text" id="raBusca" name="raBusca" required autofocus>
                        </div>
                        
                        <button type="submit" name="registrar_presenca" class="btn-primary">Registrar Presença</button>
                    </form>
                </div>
                
                <h3 style="color: var(--neon-green); margin: 30px 0 20px;">Presenças Registradas</h3>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Atividade</th>
                                <th>Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($presencas)): ?>
                                <?php foreach ($presencas as $presenca): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($presenca['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($presenca['titulo']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($presenca['data_hora'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center;">Nenhuma presença registrada ainda.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Inscritos no Evento -->
            <section id="admin-inscritos" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Inscritos no Evento</h2>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Telefone</th>
                                <th>Instituição</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($inscritos)): ?>
                                <?php foreach ($inscritos as $inscrito): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($inscrito['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($inscrito['email']); ?></td>
                                        <td><?php echo htmlspecialchars($inscrito['cpf']); ?></td>
                                        <td><?php echo !empty($inscrito['telefone']) ? htmlspecialchars($inscrito['telefone']) : 'Não informado'; ?></td>
                                        <td><?php echo htmlspecialchars($inscrito['instituicao']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Nenhum inscrito encontrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Cadastrar Atividades -->
            <section id="admin-atividades" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Cadastrar Atividades</h2>
                
                <?php if (isset($mensagem_atividade)): ?>
                <div class="message <?php echo $tipo_mensagem_atividade; ?>">
                    <?php echo $mensagem_atividade; ?>
                </div>
                <?php endif; ?>
                
                <div class="table-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="titulo">Título da Atividade</label>
                            <input type="text" id="titulo" name="titulo" required>
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
                            <input type="date" id="data" name="data" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="horario">Horário</label>
                            <input type="time" id="horario" name="horario" required>
                        </div>
                        
                        <button type="submit" name="cadastrar_atividade" class="btn-primary">Cadastrar Atividade</button>
                    </form>
                </div>
                
                <h3 style="color: var(--neon-green); margin: 30px 0 20px;">Atividades Cadastradas</h3>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Palestrante</th>
                                <th>Local</th>
                                <th>Data</th>
                                <th>Horário</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($atividades)): ?>
                                <?php foreach ($atividades as $atividade): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($atividade['titulo']); ?></td>
                                        <td><?php echo htmlspecialchars($atividade['palestrante']); ?></td>
                                        <td><?php echo htmlspecialchars($atividade['local']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($atividade['data'])); ?></td>
                                        <td><?php echo $atividade['horario']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">Nenhuma atividade cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="imagens/logo.jpg" alt="Tech Week">
                    <p style="color: var(--light-gray);">1ª TechWeek</p>
                    <p style="color: var(--tech-green); font-weight: 600; margin-top: 5px;">Semana Acadêmica de Tecnologia e Inovação</p>
                </div>
                
                <div class="footer-info">
                    <h3>Informações</h3>
                    <p><strong>Data:</strong> 28 a 31 de Outubro de 2025</p>
                    <p><strong>Horário:</strong> 14:00 às 22:00</p>
                    <p><strong>Locais:</strong> UTFPR e Teatro Municipal</p>
                    <p>Organização: UTFPR, CASIS, TypeX, CESUL</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>Todos os direitos reservados, 2025.</p>
                <p>Desenvolvido por Wellton Costa e Marcos Marcolin</p>
            </div>
        </div>
    </footer>

    <script>
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
        
        // Verificar preferência salva no localStorage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light') {
            body.classList.add('light-theme');
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        }
        
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('light-theme');
            
            if (body.classList.contains('light-theme')) {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });
        
        // Suavizar rolagem para âncoras
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Fechar menu mobile após clicar em um link
                    if (menu.classList.contains('active')) {
                        menu.classList.remove('active');
                    }
                    
                    // Fechar dropdown do usuário
                    userDropdown.classList.remove('active');
                }
            });
        });
        
        // Focar no campo de código de barras ao carregar a página de presenças
        <?php if ($isAdmin && isset($_POST['registrar_presenca'])): ?>
        document.getElementById('raBusca').focus();
        document.getElementById('raBusca').select();
        <?php endif; ?>
    </script>
</body>
</html>
