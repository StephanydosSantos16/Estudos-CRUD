<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereço = $_POST['endereço'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

  
    $sql = "SELECT COUNT(*) FROM users WHERE cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cpf]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "Este CPF já está cadastrado!";
    } else {
        $sql = "INSERT INTO users (nome, cpf, endereço, telefone, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $cpf, $endereço, $telefone, $email]);

        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#cpf').mask('000.000.000-00');
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cadastro de Cliente</h2>
        </div>
        <form method="POST">
            Nome: <input type="text" name="nome" required><br>
            CPF: <input type="text" name="cpf" id="cpf" required><br>
            Endereço: <input type="text" name="endereço" required><br>
            Telefone: <input type="text" name="telefone" id="telefone" required><br>
            Email: <input type="email" name="email" required><br>
            <button type="submit">Cadastrar</button>
        </form>
        <a href="index.php" class="button">Voltar</a>
    </div>
</body>
</html>