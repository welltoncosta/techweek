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
            --dark-bg: #121212;
            --darker-bg: #0a0a0a;
            --card-bg: #1e1e1e;
            --text-color: #f0f0f0;
            --accent-color: #6a5acd;
            --neon-green: #39ff14;
            --tech-green: #00ff88;
            --light-gray: #b0b0b0;
            --success-color: #4caf50;
            --error-color: #f44336;
            --warning-color: #ff9800;
        }

        .light-theme {
            --dark-bg: #f5f5f5;
            --darker-bg: #e0e0e0;
            --card-bg: #ffffff;
            --text-color: #333333;
            --accent-color: #5a4fcf;
            --neon-green: #00cc00;
            --tech-green: #00cc66;
            --light-gray: #666666;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-color);
            line-height: 1.6;
            transition: background-color 0.3s, color 0.3s;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header */
        header {
            background-color: var(--darker-bg);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo img {
            height: 50px;
        }

        .logo .logo-light {
            display: none;
        }

        .light-theme .logo .logo-dark {
            display: none;
        }

        .light-theme .logo .logo-light {
            display: block;
        }

        .event-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--tech-green);
        }

        .event-title span {
            font-size: 0.8rem;
            color: var(--light-gray);
        }

        .menu {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .menu a {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .menu a:hover {
            color: var(--tech-green);
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-menu {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: 1px solid var(--light-gray);
            color: var(--text-color);
            padding: 8px 15px;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .user-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 10px 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 200px;
            z-index: 1000;
            display: none;
        }

        .user-dropdown.active {
            display: block;
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: var(--text-color);
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .user-dropdown a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Main Content */
        .main-content {
            padding: 40px 0;
        }

        .welcome-section {
            margin-bottom: 40px;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--tech-green);
        }

        .welcome-section p {
            font-size: 1.1rem;
            color: var(--light-gray);
            max-width: 800px;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .dashboard-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-card i {
            font-size: 2.5rem;
            color: var(--tech-green);
            margin-bottom: 15px;
        }

        .dashboard-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: var(--light-gray);
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background-color: var(--tech-green);
            color: #000;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--neon-green);
        }

        /* User Info */
        .user-info {
            margin-bottom: 40px;
        }

        .user-info h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--tech-green);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background-color: var(--card-bg);
            padding: 15px;
            border-radius: 8px;
        }

        .info-label {
            display: block;
            color: var(--light-gray);
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-participante {
            background-color: #4caf50;
            color: white;
        }

        .badge-palestrante {
            background-color: #2196f3;
            color: white;
        }

        .badge-organizacao {
            background-color: #ff9800;
            color: white;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
            background-color: var(--darker-bg);
            color: var(--text-color);
            font-family: 'Montserrat', sans-serif;
        }

        .btn-primary {
            background-color: var(--tech-green);
            color: #000;
            border: none;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--neon-green);
        }

        .btn-primary.small {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .btn-primary:disabled {
            background-color: var(--light-gray);
            cursor: not-allowed;
        }

        /* Verification Status */
        .verification-status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px;
            background-color: var(--card-bg);
            border-radius: 8px;
        }

        .status-icon {
            font-size: 1.5rem;
        }

        .status-approved {
            color: var(--success-color);
        }

        .status-pending {
            color: var(--warning-color);
        }

        .status-rejected {
            color: var(--error-color);
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed var(--light-gray);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s;
            margin-bottom: 15px;
        }

        .file-upload:hover {
            border-color: var(--tech-green);
        }

        .file-upload i {
            font-size: 2.5rem;
            color: var(--light-gray);
            margin-bottom: 10px;
        }

        .file-upload p {
            margin-bottom: 5px;
        }

        .file-upload span {
            color: var(--light-gray);
            font-size: 0.9rem;
        }

        .file-name {
            margin-top: 10px;
            font-weight: 600;
            color: var(--tech-green);
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            margin-bottom: 30px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--darker-bg);
        }

        .data-table th {
            background-color: var(--darker-bg);
            font-weight: 600;
        }

        .data-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .verde {
            color: var(--success-color);
            font-weight: 600;
        }

        .vermelho {
            color: var(--error-color);
            font-weight: 600;
        }

        /* Certificate */
        .certificate {
            background-color: var(--card-bg);
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .certificate h2 {
            color: var(--tech-green);
            margin-bottom: 20px;
        }

        .participant-name {
            font-size: 2rem;
            font-weight: 700;
            margin: 20px 0;
            color: var(--tech-green);
        }

        .date {
            margin-top: 40px;
            font-style: italic;
        }

        /* Footer */
        footer {
            background-color: var(--darker-bg);
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-logo img {
            height: 50px;
            margin-bottom: 15px;
        }

        .footer-logo .footer-logo-light {
            display: none;
        }

        .light-theme .footer-logo .footer-logo-dark {
            display: none;
        }

        .light-theme .footer-logo .footer-logo-light {
            display: block;
        }

        .footer-info h3 {
            margin-bottom: 15px;
            color: var(--tech-green);
        }

        .footer-info p {
            margin-bottom: 8px;
            color: var(--light-gray);
        }

        .footer-bottom {
            border-top: 1px solid var(--light-gray);
            padding-top: 20px;
            text-align: center;
            color: var(--light-gray);
            font-size: 0.9rem;
        }

        .footer-bottom p {
            margin-bottom: 5px;
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: var(--tech-green);
        }

        /* Messages */
        .message {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .message.sucesso {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success-color);
        }

        .message.erro {
            background-color: rgba(244, 67, 54, 0.2);
            color: var(--error-color);
        }

        /* Crachás */
        .cracha {
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            border: 2px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
        }

        .cracha-content {
            padding: 20px;
            text-align: center;
        }

        .cracha-tipo {
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--tech-green);
        }

        .cracha-nome {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* Responsividade */
        @media (max-width: 992px) {
            .menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--darker-bg);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .menu.active {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-wrap: wrap;
            }

            .logo {
                margin-bottom: 15px;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="https://i.imgur.com/9nK8Qq2.png" alt="Logo Tech Week" class="logo-dark">
                    <img src="https://i.imgur.com/7V9X9Xq.png" alt="Logo Tech Week" class="logo-light">
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
                </ul>

                <div class="header-controls">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <div class="user-menu">
                        <button class="user-btn">
                            <i class="fas fa-user"></i>
                            Wellton
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#dados"><i class="fas fa-user-circle"></i> Meu Perfil</a>
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

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Section -->
            <section class="welcome-section">
                <h1>Olá, Wellton!</h1>
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
                        <span class="info-value">Wellton Costa</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">E-mail</span>
                        <span class="info-value">contato@wellton.com.br</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">CPF</span>
                        <span class="info-value">857.922.682-15</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Telefone</span>
                        <span class="info-value">(46) 99999-9999</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Instituição</span>
                        <span class="info-value">UTFPR</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Data de Inscrição</span>
                        <span class="info-value">24/08/2025</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Tipo de Crachá</span>
                        <span class="info-value"><span class="badge badge-participante">Participante</span></span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Código de Barras</span>
                        <span class="info-value">TW20250001</span>
                    </div>
                </div>
                
                <!-- Alterar Senha -->
                <h3 style="margin-top: 30px; margin-bottom: 20px; color: var(--neon-green);">Alterar Senha</h3>
                <div class="light-theme">
                    <h3 style="color: var(--accent-color);">Alterar Senha</h3>
                </div>
                
                <form id="change-password-form">
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn-primary">Alterar Senha</button>
                    <div id="password-message" class="message" style="display: none; margin-top: 15px;"></div>
                </form>
                
                <!-- Comprovante de Pagamento -->
                <h3 style="margin-top: 40px; margin-bottom: 20px; color: var(--neon-green);">Comprovante de Pagamento</h3>
                <div class="light-theme">
                    <h3 style="color: var(--accent-color);">Comprovante de Pagamento</h3>
                </div>
                
                <div class="verification-status">
                    <span class="status-icon"><i class="fas fa-check-circle status-approved"></i></span>
                    <span>Centro Acadêmico: <strong>Aprovado</strong></span>
                </div>
                
                <div class="verification-status">
                    <span class="status-icon"><i class="fas fa-check-circle status-approved"></i></span>
                    <span>Coordenação: <strong>Aprovado</strong></span>
                </div>
                
                <div class="file-upload" id="payment-upload">
                    <input type="file" id="payment-file" accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Clique para enviar ou arraste o comprovante PIX</p>
                    <span>Formatos aceitos: PDF, JPG, PNG (até 5MB)</span>
                    <div class="file-name" id="file-name"></div>
                </div>
                
                <button type="button" id="submit-payment" class="btn-primary" style="margin-top: 15px; display: none;">Enviar Comprovante</button>
            </section>
            
            <!-- Oficinas Section -->
            <section id="oficinas" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Minhas Oficinas</h2>
                <div class="light-theme">
                    <h2 style="color: var(--accent-color);">Minhas Oficinas</h2>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Oficina</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Local</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Workshop: Desenvolvimento Web com React Avançado</td>
                                <td>29/10/2025</td>
                                <td>14:00 - 17:00</td>
                                <td>Sala Q204 - UTFPR</td>
                                <td class="verde">Inscrito</td>
                                <td>
                                    <button class="btn-primary small" onclick="gerarCertificado(1)">Certificado</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Palestra: Inovação e Tecnologia no Google</td>
                                <td>28/10/2025</td>
                                <td>20:15 - 22:00</td>
                                <td>Auditório Central - UTFPR</td>
                                <td class="verde">Inscrito</td>
                                <td>
                                    <button class="btn-primary small" onclick="gerarCertificado(2)">Certificado</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Competição de Hackathon TechWeek</td>
                                <td>30/10/2025</td>
                                <td>19:30 - 22:00</td>
                                <td>Salas Q207-Q210 - UTFPR</td>
                                <td class="vermelho">Pendente</td>
                                <td>
                                    <button class="btn-primary small" disabled>Certificado</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h3 style="color: var(--neon-green); margin: 30px 0 20px;">Inscrever-se em Novas Oficinas</h3>
                <div class="light-theme">
                    <h3 style="color: var(--accent-color);">Inscrever-se em Novas Oficinas</h3>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Oficina</th>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Local</th>
                                <th>Vagas</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Workshop: Introdução à Blockchain e Criptomoedas</td>
                                <td>31/10/2025</td>
                                <td>09:00 - 12:00</td>
                                <td>Sala Q203 - UTFPR</td>
                                <td>15/30</td>
                                <td>
                                    <button class="btn-primary small" onclick="inscreverOficina(3)">Inscrever-se</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Workshop: Segurança Cibernética para Iniciantes</td>
                                <td>31/10/2025</td>
                                <td>14:00 - 17:00</td>
                                <td>Sala Q202 - UTFPR</td>
                                <td>22/30</td>
                                <td>
                                    <button class="btn-primary small" onclick="inscreverOficina(4)">Inscrever-se</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Certificado Section -->
            <section id="certificado" style="margin-top: 40px;">
                <h2 style="color: var(--neon-green); margin-bottom: 20px; font-size: 1.8rem;">Certificado de Participação</h2>
                <div class="light-theme">
                    <h2 style="color: var(--accent-color);">Certificado de Participação</h2>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-certificate" style="font-size: 3rem;"></i>
                    <h3>Certificado da 1ª TechWeek</h3>
                    <p>Seu certificado de participação estará disponível para download após o término do evento e confirmação de presença nas atividades.</p>
                    <button class="btn" onclick="gerarCertificadoGeral()" id="certificado-geral-btn">Gerar Certificado Geral</button>
                </div>
                
                <div id="certificado-container" style="display: none; margin-top: 30px;">
                    <!-- Certificate will be generated here -->
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="https://i.imgur.com/9nK8Qq2.png" alt="Tech Week" class="footer-logo-dark">
                    <img src="https://i.imgur.com/7V9X9Xq.png" alt="Tech Week" class="footer-logo-light">
                    <p style="color: var(--light-gray);">1ª TechWeek</p>
                    <p style="color: var(--tech-green); font-weight: 600; margin-top: 5px;">Semana Acadêmica de Tecnologia e Inovação</p>
                </div>
                
                <div class="footer-info">
                    <h3>Informações</h3>
                    <p><strong>Data:</strong> 28 a 31 de Outubro de 2025</p>
                    <p><strong>Horário:</strong> 14:00 às 22:00</p>
                    <p><strong>Locais:</strong> UTFPR e Teatro Municipal</p>
                    <p>Organização: UTFPR, TechWeek, Nubetec, TypeX, CESUL</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>Todos os direitos reservados, 2025.</p>
                <p>Desenvolvido por Wellton Costa e Marcos Marcolin</p>
            </div>
        </div>
    </footer>

    <!-- Modal para visualização de crachá -->
    <div class="modal" id="cracha-modal">
        <div class="modal-content">
            <h3>Crachá do Participante</h3>
            <div id="cracha-content"></div>
            <button onclick="fecharModal('cracha-modal')">Fechar</button>
            <button onclick="imprimirCracha()" style="margin-left: 10px;"><i class="fas fa-print"></i> Imprimir</button>
        </div>
    </div>

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
        
        // Upload de comprovante
        const paymentUpload = document.getElementById('payment-upload');
        const paymentFile = document.getElementById('payment-file');
        const fileName = document.getElementById('file-name');
        const submitPayment = document.getElementById('submit-payment');
        
        paymentUpload.addEventListener('click', () => {
            paymentFile.click();
        });
        
        paymentFile.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileName.textContent = e.target.files[0].name;
                submitPayment.style.display = 'block';
            } else {
                fileName.textContent = '';
                submitPayment.style.display = 'none';
            }
        });
        
        submitPayment.addEventListener('click', () => {
            // Simular upload do comprovante
            alert('Comprovante enviado com sucesso! Aguarde a validação.');
            submitPayment.style.display = 'none';
            fileName.textContent = '';
            paymentFile.value = '';
        });
        
        // Alteração de senha
        const changePasswordForm = document.getElementById('change-password-form');
        const passwordMessage = document.getElementById('password-message');
        
        changePasswordForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const senhaAtual = document.getElementById('senha_atual').value;
            const novaSenha = document.getElementById('nova_senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (novaSenha !== confirmarSenha) {
                passwordMessage.textContent = 'As senhas não coincidem!';
                passwordMessage.className = 'message erro';
                passwordMessage.style.display = 'block';
                return;
            }
            
            // Simular alteração de senha
            passwordMessage.textContent = 'Senha alterada com sucesso!';
            passwordMessage.className = 'message sucesso';
            passwordMessage.style.display = 'block';
            
            // Limpar formulário
            changePasswordForm.reset();
        });
        
        // Funções para modais
        function abrirModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        
        function fecharModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        
        function gerarCracha(id, tipo) {
            const crachaContent = document.getElementById('cracha-content');
            const nome = tipo === 'participante' ? 'Wellton Costa' : 
                         tipo === 'palestrante' ? 'Carlos Silva' : 'Ana Santos';
            const codigo = tipo === 'participante' ? 'TW20250001' : 
                          tipo === 'palestrante' ? 'TW20250002' : 'TW20250003';
            
            crachaContent.innerHTML = `
                <div class="cracha cracha-${tipo}">
                    <div class="cracha-content">
                        <div class="cracha-tipo">${tipo.charAt(0).toUpperCase() + tipo.slice(1)}</div>
                        <div class="cracha-nome">${nome}</div>
                        <div class="cracha-codigo">
                            <svg id="barcode-${id}"></svg>
                        </div>
                    </div>
                </div>
            `;
            
            // Gerar código de barras
            setTimeout(() => {
                JsBarcode(`#barcode-${id}`, codigo, {
                    format: "CODE128",
                    lineColor: "#000",
                    width: 2,
                    height: 40,
                    displayValue: true
                });
            }, 100);
            
            abrirModal('cracha-modal');
        }
        
        function imprimirCracha() {
            const conteudo = document.getElementById('cracha-content').innerHTML;
            const janela = window.open('', '_blank');
            janela.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Imprimir Crachá</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .cracha { 
                            width: 300px; 
                            height: 180px; 
                            border: 1px solid #000; 
                            padding: 15px; 
                            margin: 20px; 
                        }
                    </style>
                </head>
                <body>
                    ${conteudo}
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                   <\/script>
                </body>
                </html>
            `);
            janela.document.close();
        }
        
        function gerarCertificado(id) {
            let certificadoContainer = document.getElementById('certificado-container');
            
            if (!certificadoContainer) {
                certificadoContainer = document.createElement('div');
                certificadoContainer.id = 'certificado-container';
                document.querySelector('#certificado').appendChild(certificadoContainer);
            }
            
            const nome = id === 1 ? 'Wellton Costa' : 
                        id === 2 ? 'Carlos Silva' : 'Ana Santos';
            const atividade = id === 1 ? 'Workshop: Desenvolvimento Web com React Avançado' :
                            id === 2 ? 'Palestra: Inovação e Tecnologia no Google' :
                            'Organização do Evento';
            
            certificadoContainer.innerHTML = `
                <div class="certificate">
                    <h2>Certificado de Participação</h2>
                    <p>Certificamos que</p>
                    <div class="participant-name">${nome}</div>
                    <p>participou da ${atividade}</p>
                    <p>durante a 1ª TechWeek - Semana Acadêmica de Tecnologia e Inovação</p>
                    <p>realizada de 28 a 31 de Outubro de 2025</p>
                    <div class="date">
                        <p>Francisco Beltrão, PR, Brasil<br>31 de Outubro de 2025</p>
                    </div>
                </div>
                <button class="btn-primary" onclick="imprimirCertificado()" style="margin-top: 20px;">
                    <i class="fas fa-print"></i> Imprimir Certificado
                </button>
            `;
            
            certificadoContainer.style.display = 'block';
        }
        
        function gerarCertificadoGeral() {
            let certificadoContainer = document.getElementById('certificado-container');
            
            if (!certificadoContainer) {
                certificadoContainer = document.createElement('div');
                certificadoContainer.id = 'certificado-container';
                document.querySelector('#certificado').appendChild(certificadoContainer);
            }
            
            certificadoContainer.innerHTML = `
                <div class="certificate">
                    <h2>Certificado de Participação</h2>
                    <p>Certificamos que</p>
                    <div class="participant-name">Wellton Costa</div>
                    <p>participou da 1ª TechWeek - Semana Acadêmica de Tecnologia e Inovação</p>
                    <p>como participante, com carga horária total de 20 horas</p>
                    <p>realizada de 28 a 31 de Outubro de 2025</p>
                    <div class="date">
                        <p>Francisco Beltrão, PR, Brasil<br>31 de Outubro de 2025</p>
                    </div>
                </div>
                <button class="btn-primary" onclick="imprimirCertificado()" style="margin-top: 20px;">
                    <i class="fas fa-print"></i> Imprimir Certificado
                </button>
            `;
            
            certificadoContainer.style.display = 'block';
            document.getElementById('certificado-geral-btn').style.display = 'none';
        }
        
        function imprimirCertificado() {
            const conteudo = document.getElementById('certificado-container').innerHTML;
            const janela = window.open('', '_blank');
            janela.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Imprimir Certificado</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .certificate { 
                            background: white;
                            padding: 30px;
                            border: 15px solid #f1c40f;
                            text-align: center;
                            margin: 20px;
                        }
                        .participant-name {
                            font-size: 2rem;
                            font-weight: bold;
                            margin: 30px 0;
                        }
                    </style>
                </head>
                <body>
                    ${conteudo}
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
                </html>
            `);
            janela.document.close();
        }
        
        function inscreverOficina(id) {
            // Simular inscrição em oficina
            alert(`Inscrição na oficina ${id} realizada com sucesso!`);
        }
        
        // Logout
        document.getElementById('logout-btn').addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'index.html';
            }
        });
    </script>
</body>
</html>
