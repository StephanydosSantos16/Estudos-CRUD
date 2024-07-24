<?php
include 'db.php';


function formatar_cpf($cpf) {
    $cpf_formatado = preg_replace('/[^0-9]/', '', $cpf); 
    $cpf_formatado = substr_replace($cpf_formatado, '-', 3, 0); 
    $cpf_formatado = substr_replace($cpf_formatado, '.', 7, 0); 
    $cpf_formatado = substr_replace($cpf_formatado, '.', 11, 0); 
    return $cpf_formatado;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    header("Location: index.php");
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereço = $_POST['endereço'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $cpf_formatado = formatar_cpf($cpf);

    $sql = "UPDATE users SET nome = ?, cpf = ?, endereço = ?, telefone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $cpf_formatado, $endereço, $telefone, $email, $id]);

    header("Location: index.php");
}

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereço = $_POST['endereço'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

   
    $cpf_formatado = formatar_cpf($cpf);

    $sql = "INSERT INTO users (nome, cpf, endereço, telefone, email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome, $cpf_formatado, $endereço, $telefone, $email]);

    header("Location: index.php");
}

$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Clientes</title>
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
            <h2>Clientes</h2>
            <a href="login.php" class="button">Ir para Login</a>
        </div>
        <a href="create.php" class="button">Cadastrar Novo Cliente</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['nome']; ?></td>
                <td><?php echo $user['cpf']; ?></td>
                <td><?php echo $user['endereço']; ?></td>
                <td><?php echo $user['telefone']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <a href="index.php?edit=<?php echo $user['id']; ?>">Editar</a>
                    <a href="index.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php if (isset($_GET['edit'])): ?>
        <?php
        $id = $_GET['edit'];
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        ?>
        <h2>Editar Cliente</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            Nome: <input type="text" name="nome" value="<?php echo $user['nome']; ?>" required><br>
            CPF: <input type="text" name="cpf" id="cpf" value="<?php echo $user['cpf']; ?>" required><br>
            Endereço: <input type="text" name="endereço" value="<?php echo $user['endereço']; ?>" required><br>
            Telefone: <input type="text" name="telefone" id="telefone" value="<?php echo $user['telefone']; ?>" required><br>
            Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
            <button type="submit" name="update">Salvar</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>