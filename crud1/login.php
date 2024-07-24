<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #d7eaff 0%, #b4d8ff 100%); 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #343a40; 
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); 
            text-align: center;
            background: #ffffff; 
            border: 1px solid #dee2e6;
            box-sizing: border-box;
            animation: fadeIn 0.5s ease-in-out;
        }

        .login-title {
            font-size: 32px;
            margin-bottom: 25px;
            color: #007bff; 
            font-weight: 700;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057; 
        }

        .input-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
            outline: none;
        }

        .input-group input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, #28a745 0%, #218838 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
            box-sizing: border-box;
            outline: none;
        }

        button:hover {
            background: linear-gradient(90deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-2px);
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 500;
            transition: opacity 0.3s ease-in-out;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info {
            background-color: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

    
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            animation: fadeIn 0.5s ease-in-out;
        }

        .hidden {
            opacity: 0;
        }

        .visible {
            opacity: 1;
        }

    </style>
    <script>
        
        window.onload = function() {
            document.getElementById("cpf").value = "";
            document.getElementById("senha_adm").value = "";
        };
    </script>
</head>
<body>
    <div class="container">
        <h2 class="login-title">Bem-vindo! Faça seu Login</h2>
        <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off">
            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" placeholder="Nome" required autocomplete="new-name">
            </div>
            <div class="input-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="CPF" required autocomplete="new-password">
            </div>
            <div class="input-group">
                <label for="senha_adm">Senha de Administrador (opcional)</label>
                <input type="password" id="senha_adm" name="senha_adm" placeholder="Senha de Administrador" autocomplete="new-password">
            </div>
            <button type="submit">Entrar</button>
            <div class="message <?php if (!empty($message_type)) echo $message_type; ?>">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["nome"]) && isset($_POST["cpf"])) {
                        
                        $conexao = mysqli_connect("localhost", "stephany", "12345", "crud");

                        
                        if (mysqli_connect_errno()) {
                            die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
                        }

                        $nome = $_POST["nome"];
                        $cpf = $_POST["cpf"];
                        $senha_adm = isset($_POST["senha_adm"]) ? $_POST["senha_adm"] : "";

                        
                        $is_admin = false;
                        if ($senha_adm == "12345") {
                            $is_admin = true;
                        }

                        
                        $query = "SELECT * FROM users WHERE nome = ? AND cpf = ?";
                        $stmt = mysqli_prepare($conexao, $query);
                        
                        
                        if ($stmt === false) {
                            die('Erro na preparação da declaração: ' . mysqli_error($conexao));
                        }

                        
                        mysqli_stmt_bind_param($stmt, "ss", $nome, $cpf);
                        
                        mysqli_stmt_execute($stmt);
                
                        $resultado = mysqli_stmt_get_result($stmt);

                        
                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            if ($is_admin) {
                                echo '<p class="success">Login feito como administrador!</p>';
                            } else {
                                echo '<p class="success">Login feito!</p>';
                            }
                           
                        } else {
                            echo '<p class="error">Você não possui cadastro ou não é um administrador válido.</p>';
                        }

                        
                        mysqli_stmt_close($stmt);
                     
                        mysqli_close($conexao);
                    } else {
                        echo '<p class="info">Por favor, preencha todos os campos corretamente.</p>';
                    }
                }
                ?>
            </div>
        </form>
    </div>
</body>
</html>