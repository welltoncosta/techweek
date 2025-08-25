<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração - 1ª TechWeek</title>
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
            --accent-color: #00BF63;
            --accent-hover: #00FF00;
            --error-color: #ff3860;
            --success-color: #09c372;
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
            grid-template-columns: 250px 1fr;
            min-height: calc(100vh - 180px);
            flex: 1;
        }
        
        .admin-sidebar {
            background-color: var(--black);
            padding: 20px;
            border-right: 1px solid var(--border-color);
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
            color: var(--accent-color);
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
        
        /* Cracha Styles */
        .cracha-container {
            width: 100%;
            max-width: 300px;
            background: white;
            color: black;
            padding: 15px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .cracha-nome {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .cracha-codigo {
            font-family: monospace;
            font-size: 14px;
            letter-spacing: 1px;
        }
        
        /* Media Queries */
        @media (max-width: 992px) {
            .admin-panel {
                grid-template-columns: 1fr;
            }
            
            .admin-sidebar {
                display: none;
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
        }
    </style>
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
                        <span>Painel de Administração</span>
                    </div>
                </div>
                
                <ul class="menu">
                    <li><a href="#dashboard" class="admin-nav active" data-section="dashboard">Dashboard</a></li>
                    <li><a href="#participantes" class="admin-nav" data-section="participantes">Participantes</a></li>
                    <li><a href="#atividades" class="admin-nav" data-section="atividades">Atividades</a></li>
                    <li><a href="#presencas" class="admin-nav" data-section="presencas">Presenças</a></li>
                    <li><a href="#comprovantes" class="admin-nav" data-section="comprovantes">Comprovantes</a></li>
                    <li><a href="#relatorios" class="admin-nav" data-section="relatorios">Relatórios</a></li>
                </ul>

                <div class="header-controls">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    
                    <div class="user-menu">
                        <button class="user-btn">
                            <i class="fas fa-user"></i>
                            Wellton Costa De Oliveira
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
                <li><a href="#relatorios" class="admin-nav" data-section="relatorios">Relatórios</a></li>
            </ul>
        </div>
        
        <div class="admin-content">
            <!-- Seção Dashboard -->
            <section id="dashboard-section" class="admin-section active">
                <h2>Dashboard</h2>
                
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <i class="fas fa-users"></i>
                        <h3>Total de Participantes</h3>
                        <p>1</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Total de Atividades</h3>
                        <p>1</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Presenças Confirmadas</h3>
                        <p>0</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Comprovantes Pendentes</h3>
                        <p>0</p>
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
                            <tr>
                                <td><span class="badge badge-participante">Participante</span></td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Seção de Participantes -->
            <section id="participantes-section" class="admin-section">
                <h2>Gerenciar Participantes</h2>
                
                <div class="table-container">
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
                            <tr>
                                <td>Wellton Costa De Oliveira</td>
                                <td>contato@wellton.com.br</td>
                                <td>857.922.682-15</td>
                                <td></td>
                                <td></td>
                                <td>
                                    <span class="badge badge-participante">
                                        Participante
                                    </span>
                                </td>
                                <td>105211</td>
                                <td>
                                    <label class="admin-checkbox">
                                        <input type="checkbox" class="admin-toggle" 
                                            data-id="1" 
                                            checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <button class="btn-primary btn-small" onclick="editarParticipante(1)">Editar</button>
                                    <button class="btn-primary btn-small" onclick="gerarCracha(1, 'participante')">Crachá</button>
                                    <button class="btn-primary btn-small" onclick="gerarCertificado(1)">Certificado</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Modal de Edição de Participante -->
                <div class="modal" id="editar-participante-modal">
                    <div class="modal-content">
                        <h3>Editar Participante</h3>
                        <form id="editar-participante-form">
                            <input type="hidden" name="csrf_token" value="38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def">
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
                                    <option value="palestrante">Palestrante</option>
                                    <option value="coordenacao">Coordenação</option>
                                    <option value="centro_academico">Centro Acadêmico</option>
                                    <option value="typex">TypeX</option>
                                    <option value="apoyo">Apoio</option>
                                    <option value="organizacao">Organização</option>
                                </select>
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
                
                <div class="table-container">
                    <h3>Cadastrar Nova Atividade</h3>
                    <form id="cadastrar-atividade-form">
                        <input type="hidden" name="csrf_token" value="38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def">
                        <input type="hidden" name="action" value="cadastrar_atividade">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" id="titulo" name="titulo" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select id="tipo" name="tipo" required>
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
                            <input type="date" id="data" name="data" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_inicio">Hora Início</label>
                            <input type="time" id="hora_inicio" name="hora_inicio" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_fim">Hora Fim</label>
                            <input type="time" id="hora_fim" name="hora_fim" required>
                        </div>
                        <div class="form-group">
                            <label for="vagas">Vagas</label>
                            <input type="number" id="vagas" name="vagas" min="1" required>
                        </div>
                        <button type="submit" class="btn-primary">Cadastrar Atividade</button>
                    </form>
                </div>
                                
                <div class="table-container">
                    <h3>Atividades Cadastradas</h3>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>teste</td>
                                <td>Palestra</td>
                                <td>tetete</td>
                                <td>teste</td>
                                <td>05/09/2025</td>
                                <td>23:35 - 17:31</td>
                                <td>5445</td>
                                <td>0</td>
                                <td>
                                    <button class="btn-primary btn-small" onclick="editarAtividade(1)">Editar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Modal de Edição de Atividade -->
                <div class="modal" id="editar-atividade-modal">
                    <div class="modal-content">
                        <h3>Editar Atividade</h3>
                        <form id="editar-atividade-form">
                            <input type="hidden" name="csrf_token" value="38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def">
                            <input type="hidden" name="action" value="editar_atividade">
                            <input type="hidden" id="edit-atividade-id" name="id">
                            <div class="form-group">
                                <label for="edit-titulo">Título</label>
                                <input type="text" id="edit-titulo" name="titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-tipo">Tipo</label>
                                <select id="edit-tipo" name="tipo" required>
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
                                <input type="date" id="edit-data" name="data" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-hora_inicio">Hora Início</label>
                                <input type="time" id="edit-hora_inicio" name="hora_inicio" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-hora_fim">Hora Fim</label>
                                <input type="time" id="edit-hora_fim" name="hora_fim" required>
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
                
                <div class="table-container">
                    <form id="registrar-presenca-form">
                        <input type="hidden" name="csrf_token" value="38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def">
                        <input type="hidden" name="action" value="registrar_presenca">
                        <div class="form-group">
                            <label for="atividade">Atividade</label>
                            <select id="atividade" name="atividade_id" required>
                                <option value="">Selecione uma atividade</option>
                                <option value="1">
                                    teste - 
                                    05/09/2025 - 
                                    23:35
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="codigo_barras">Código de Barras</label>
                            <input type="text" id="codigo_barras" name="codigo_barras" required autofocus>
                        </div>
                        <button type="submit" class="btn-primary">Registrar Presença</button>
                    </form>
                </div>
                
                <h3>Presenças Registradas</h3>
                <div class="table-container">
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
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- Seção de Comprovantes -->
            <section id="comprovantes-section" class="admin-section">
                <h2>Validação de Comprovantes</h2>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Participante</th>
                                <th>Data Envio</th>
                                <th>Status CA</th>
                                <th>Status Coordenação</th>
                                <th>Comprovante</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                
                <!-- Modal para visualização de comprovante -->
                <div class="modal" id="comprovante-modal">
                    <div class="modal-content">
                        <h3>Comprovante de Pagamento</h3>
                        <div id="comprovante-content">
                            <p>Carregando comprovante...</p>
                        </div>
                        <button onclick="fecharModal('comprovante-modal')">Fechar</button>
                    </div>
                </div>
            </section>
            
            <!-- Seção de Relatórios -->
            <section id="relatorios-section" class="admin-section">
                <h2>Relatórios</h2>
                
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <i class="fas fa-users"></i>
                        <h3>Total de Participantes</h3>
                        <p>1</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Total de Atividades</h3>
                        <p>1</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-check-circle"></i>
                        <h3>Presenças Confirmadas</h3>
                        <p>0</p>
                    </div>
                    
                    <div class="dashboard-card">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Comprovantes Pendentes</h3>
                        <p>0</p>
                    </div>
                </div>
                
                <div class="table-container">
                    <h3>Participantes por Tipo</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-participante">Participante</span></td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="table-container">
                    <h3>Atividades por Tipo</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Palestra</td>
                                <td>1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="https://i.imgur.com/9nK8Qq2.png" alt="Logo Tech Week" class="footer-logo-dark">
                    <img src="https://i.imgur.com/7V9X9Xq.png" alt="Logo Tech Week" class="footer-logo-light">
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

    <script>
        // Navegação do painel de admin
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
                
                // Fechar menu mobile se estiver aberto
                menu.classList.remove('active');
            });
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
        
        // Editar participante
        function editarParticipante(id) {
            // Buscar dados do participante via AJAX
            const formData = new FormData();
            formData.append('action', 'buscar_participante');
            formData.append('id', id);
            formData.append('csrf_token', '38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def');
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit-id').value = data.participante.id;
                    document.getElementById('edit-nome').value = data.participante.nome;
                    document.getElementById('edit-email').value = data.participante.email;
                    document.getElementById('edit-cpf').value = data.participante.cpf;
                    document.getElementById('edit-telefone').value = data.participante.telefone || '';
                    document.getElementById('edit-instituicao').value = data.participante.instituicao || '';
                    document.getElementById('edit-tipo').value = data.participante.tipo;
                    
                    abrirModal('editar-participante-modal');
                } else {
                    alert('Erro ao carregar dados: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        
        // Formulário de edição de participante
        document.getElementById('editar-participante-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Dados atualizados com sucesso!');
                    fecharModal('editar-participante-modal');
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
        
        // Toggle administrador
        document.querySelectorAll('.admin-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isAdmin = this.checked;
                
                const formData = new FormData();
                formData.append('action', 'toggle_admin');
                formData.append('id', id);
                formData.append('administrador', isAdmin);
                formData.append('csrf_token', '38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def');
                
                fetch('painel.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Erro: ' + data.message);
                        this.checked = !isAdmin;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    this.checked = !isAdmin;
                });
            });
        });
        
        // Cadastrar atividade
        document.getElementById('cadastrar-atividade-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Atividade cadastrada com sucesso!');
                    this.reset();
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
        
        // Editar atividade
        function editarAtividade(id) {
            // Buscar dados da atividade via AJAX
            const formData = new FormData();
            formData.append('action', 'buscar_atividade');
            formData.append('id', id);
            formData.append('csrf_token', '38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def');
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('edit-atividade-id').value = data.atividade.id;
                    document.getElementById('edit-titulo').value = data.atividade.titulo;
                    document.getElementById('edit-tipo').value = data.atividade.tipo;
                    document.getElementById('edit-palestrante').value = data.atividade.palestrante;
                    document.getElementById('edit-local').value = data.atividade.local;
                    
                    // Formatar data para YYYY-MM-DD
                    const dataParts = data.atividade.data.split('/');
                    const dataFormatada = `${dataParts[2]}-${dataParts[1]}-${dataParts[0]}`;
                    document.getElementById('edit-data').value = dataFormatada;
                    
                    document.getElementById('edit-hora_inicio').value = data.atividade.hora_inicio;
                    document.getElementById('edit-hora_fim').value = data.atividade.hora_fim;
                    document.getElementById('edit-vagas').value = data.atividade.vagas;
                    
                    abrirModal('editar-atividade-modal');
                } else {
                    alert('Erro ao carregar dados: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        
        // Formulário de edição de atividade
        document.getElementById('editar-atividade-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Atividade atualizada com sucesso!');
                    fecharModal('editar-atividade-modal');
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
        
        // Registrar presença
        document.getElementById('registrar-presenca-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Presença registrada com sucesso!');
                    this.reset();
                    document.getElementById('codigo_barras').focus();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        });
        
        // Validar pagamento
        function validarPagamento(id, tipo, aprovado) {
            if (!confirm(`Tem certeza que deseja ${aprovado ? 'aprovar' : 'reprovar'} este comprovante?`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'validar_pagamento');
            formData.append('id', id);
            formData.append('tipo', tipo);
            formData.append('aprovado', aprovado);
            formData.append('csrf_token', '38ac7fa8208f855eb2c9cccbab296202b6bfcbb4f00b897f798f69f231f55def');
            
            fetch('painel.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Validação atualizada com sucesso!');
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
            });
        }
        
        // Ver comprovante
        function verComprovante(id) {
            // Implementar visualização de comprovante
            alert('Visualização de comprovante em desenvolvimento. ID: ' + id);
        }
        
        // Gerar crachá
        function gerarCracha(id, tipo) {
            // Abrir em uma nova janela com o crachá simplificado
            const crachaWindow = window.open('', '_blank', 'width=300,height=200');
            crachaWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Crachá - 1ª TechWeek</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 15px;
                            background: white;
                            color: black;
                            text-align: center;
                        }
                        .cracha-nome {
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 10px;
                        }
                        .cracha-codigo {
                            font-family: monospace;
                            font-size: 14px;
                            letter-spacing: 1px;
                        }
                    </style>
                </head>
                <body>
                    <div class="cracha-nome">Wellton Costa De Oliveira</div>
                    <div class="cracha-codigo">105211</div>
                </body>
                </html>
            `);
            crachaWindow.document.close();
        }
        
        // Gerar certificado
        function gerarCertificado(id, atividade_id = null) {
            let url = `painel.php?action=gerar_certificado&id=${id}`;
            if (atividade_id) {
                url += `&atividade_id=${atividade_id}`;
            }
            window.open(url, '_blank');
        }
        
        // Funções auxiliares para modais
        function abrirModal(id) {
            document.getElementById(id).style.display = 'flex';
        }
        
        function fecharModal(id) {
            document.getElementById(id).style.display = 'none';
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
