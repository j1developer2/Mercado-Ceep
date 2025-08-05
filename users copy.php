<?php
    ob_start();
    session_start();
    $pageTitle = 'Profile';
    include 'init.php';

    // Verifica se o usuário está logado
    if (!isset($_SESSION['user'])) {
        header('Location: login.php'); // Redireciona para a página de login se não estiver logado
        exit();
    }

    $userId = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

    // Obtém o ID do usuário atual
    //$userId = $_SESSION['uid'];

    // Consulta SQL para recuperar informações do usuário
    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ?");
    $stmt->execute(array($userId));
    $user = $stmt->fetch();

    // Exibe informações do perfil do usuário
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Adicione cabeçalhos HTML, links CSS, etc., conforme necessário -->
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <div class="container">
        <h1 class="text-center"><?php echo $pageTitle; ?></h1>
        <div class="row">
            <div class="col-md-3">
                <?php
                    // Exibe a imagem do perfil (substitua pelo caminho real do arquivo, se necessário)
                    echo "<img class='img-responsive img-thumbnail center-block' src='admin/uploads/avatars/" . $user['avatar'] . "' alt='Profile Image' />";
                ?>
            </div>
            <div class="col-md-9">
                <!-- Exiba outras informações do perfil do usuário -->
                <p><strong>Nome de Usuário:</strong> <?php echo $user['Username']; ?></p>
                <p><strong>Nome Completo:</strong> <?php echo $user['FullName']; ?></p>
                <p><strong>Turma:</strong> <?php echo $user['Turma']; ?></p>
                <p><strong>Turno:</strong> <?php echo $user['Turno']; ?></p>
                <!-- Adicione mais informações do perfil conforme necessário -->
            </div>
        </div>
    </div>
    <?php include $tpl . 'footer.php'; ?>
</body>
</html>
<?php
    ob_end_flush();
?>
