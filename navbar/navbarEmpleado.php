<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playwrite+DK+Loopet:wght@100..400&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #2e245c;
            --secondary-color: #4a3f8b;
            --accent-color: #e5e0ff;
            --text-light: #ffffff;
            --transition-speed: 0.3s;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            height: 6%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 0.8rem 0;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-light);
            text-decoration: none;
            transition: transform var(--transition-speed) ease;
        }

        .navbar-logo:hover {
            transform: translateY(-2px);
        }

        .navbar-logo img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1.5rem;
        }

        .navbar-item {
            position: relative;
        }

        .navbar-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.5rem 0;
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-link::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--accent-color);
            transition: width var(--transition-speed) ease;
        }

        .navbar-link:hover {
            color: var(--accent-color);
        }

        .navbar-link:hover::before {
            width: 100%;
        }

        .navbar-link.active {
            color: var(--accent-color);
        }

        .navbar-link.active::before {
            width: 100%;
        }

        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                padding: 1rem;
            }

            .navbar-menu {
                flex-direction: column;
                align-items: center;
                width: 100%;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .navbar-item {
                width: 100%;
                text-align: center;
            }

            .navbar-link {
                padding: 0.8rem 0;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="navbar-container">
                <a href="../manejoClientes/mostrarClientes.php" class="navbar-logo">
                    <img src="../images/logo.png" alt="Logo del Banco">
                    <span>Banco Nacional</span>
                </a>
                <ul class="navbar-menu">
                    <li class="navbar-item">
                        <a href="../manejoClientes/mostrarClientes.php" class="navbar-link active">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path
                                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z" />
                            </svg>
                            Inicio
                        </a>
                    </li>
                    <li class="navbar-item">
                        <a href="../manejoCuentas/mostrarEmpleado.php" class="navbar-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                <path fill-rule="evenodd"
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                            </svg>
                            Cuentas
                        </a>
                    </li>
                    <li class="navbar-item">
                        <a href="../prestamos/mostrarPrestamos.php" class="navbar-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                <path
                                    d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z" />
                            </svg>
                            Préstamos
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</body>

</html>