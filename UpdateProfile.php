<?php

session_start();
include 'init.php';

// Certifique-se que as funções redimensionarImagem e getSingleValue estão em init.php
// Se não estiverem, adicione-as no início deste arquivo ou em init.php.

echo "<h1 class='text-center'>Atualizar Perfil</h1>";
echo "<div class='container'>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get Variables From The Form
    $id     = $_POST['userid'];
    $user   = $_POST['username'];
    $email  = $_POST['email'];
    $name   = $_POST['full'];
    $turma  = $_POST['turma'];
    $turno  = $_POST['turno'];

    // NOVO: Pega as informações do arquivo de avatar
    $avatarFile = $_FILES['avatar'];

    // Password Trick
    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

    // Validate The Form
    $formErrors = array();
    if (strlen($user) < 4) {
        $formErrors[] = 'O nome de usuário precisa ter mais de <strong>4 caracteres</strong>';
    }
    if (strlen($user) > 20) {
        $formErrors[] = 'O nome de usuário não pode ter mais de <strong>20 caracteres</strong>';
    }
    if (empty($user)) {
        $formErrors[] = 'O nome de usuário não pode ficar <strong>vazio</strong>';
    }
    if (empty($name)) {
        $formErrors[] = 'O nome completo não pode ficar <strong>vazio</strong>';
    }
    if (empty($email)) {
        $formErrors[] = 'O email não pode ficar <strong>vazio</strong>';
    }

    // Loop Into Errors Array And Echo It
    foreach($formErrors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }

    // Check If There's No Error Proceed The Update Operation
    if (empty($formErrors)) {

        // Verifica se o nome de usuário já existe para outro usuário
        $stmt2 = $con->prepare("SELECT UserID FROM users WHERE Username = ? AND UserID != ?");
        $stmt2->execute(array($user, $id));
        $count = $stmt2->rowCount();

        if ($count == 1) {
            echo '<div class="alert alert-danger">Desculpe, este nome de usuário já está em uso por outra pessoa.</div>';
        } else {

            // ALTERADO: Lógica para lidar com o upload da imagem
            $avatar_sql_part = ""; // Parte do SQL que será adicionada se houver um novo avatar
            $avatar_param = null;  // Parâmetro do avatar para a query

            if (!empty($avatarFile['name'])) {
                
                $avatarName = $avatarFile['name'];
                $avatarTmp  = $avatarFile['tmp_name'];
                
                $random_number = rand(0, 100000000);
                $new_avatar_name = $random_number . '_' . $avatarName;
                $destination_path = "admin/uploads/avatars/" . $new_avatar_name;

                if (redimensionarImagem($avatarTmp, $destination_path, 225, 225)) {
                    // Se o upload e redimensionamento funcionarem
                    $avatar_sql_part = ", avatar = ?";
                    $avatar_param = $new_avatar_name;

                    // Opcional, mas recomendado: Excluir a foto de perfil antiga
                    $oldAvatar = getSingleValue($con, "SELECT avatar FROM users WHERE UserID = ?", [$id]);
                    if (!empty($oldAvatar) && file_exists("admin/uploads/avatars/" . $oldAvatar)) {
                        unlink("admin/uploads/avatars/" . $oldAvatar);
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erro ao processar a nova imagem. A foto não foi atualizada.</div>";
                }
            }
            
            // ALTERADO: A query agora é dinâmica
            $stmt = $con->prepare("UPDATE users SET 
                                        Username = ?, 
                                        Email = ?, 
                                        FullName = ?, 
                                        Turma = ?, 
                                        Turno = ?, 
                                        Password = ? 
                                        $avatar_sql_part 
                                    WHERE UserID = ?");

            // ALTERADO: Os parâmetros agora são montados em um array
            $params = [$user, $email, $name, $turma, $turno, $pass];
            if ($avatar_param !== null) {
                $params[] = $avatar_param; // Adiciona o nome do avatar se ele foi atualizado
            }
            $params[] = $id; // Adiciona o UserID no final

            $stmt->execute($params);

            // Echo Success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' registro atualizado com sucesso.</div>';
            echo $theMsg;

            // Atualiza a sessão com o novo nome de usuário, caso tenha sido alterado
            $_SESSION['user'] = $user;

            $seconds = 3;
            echo "<div class='alert alert-info'>Você será redirecionado para o seu perfil em $seconds segundos.</div>";
            header("refresh:$seconds;url='profile.php'"); // Supondo que a página de perfil seja profile.php
            exit();
        }
    }

} else {
    $theMsg = '<div class="alert alert-danger">Desculpe, você não pode acessar esta página diretamente.</div>';
    redirectHome($theMsg); // Supondo que você tenha essa função em init.php
}

echo "</div>";
include $tpl . 'footer.php';
?>