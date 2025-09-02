<?php
// my_chats.php
ob_start();
session_start();
$pageTitle = 'Minhas Conversas';
include 'init.php'; // Inclui o cabeçalho e a conexão com o banco

// Se o usuário não estiver logado, redireciona para o login
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

// ======================================================================
// CSS PARA ESTILIZAR A PÁGINA DE CHATS
// ======================================================================
?>
<style>
    /* Estilos para a lista de chats (my_chats.php) */
    .chat-list {
        max-width: 800px;
        margin: 30px auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        background-color: #fff;
    }

    .chat-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        color: #333;
        transition: background-color 0.2s;
    }

    .chat-item:last-child {
        border-bottom: none;
    }

    .chat-item:hover {
        background-color: #f9f9f9;
        text-decoration: none;
        color: #333;
    }

    .chat-item-avatar img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        margin-right: 15px;
        border-radius: 50%; /* Garante que a imagem seja um círculo */
    }

    .chat-item-details {
        flex-grow: 1;
        overflow: hidden; /* Evita que o texto saia do container */
    }

    .chat-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .chat-item-header strong {
        font-size: 1.1em;
    }
    
    .chat-item-header small {
        color: #999;
    }

    p.last-message {
        margin: 0;
        color: #777;
        /* As 3 linhas abaixo garantem que o texto não quebre o layout */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<?php
// ======================================================================
// FIM DO CSS
// ======================================================================

$current_user_id = $_SESSION['uid'];

// Prepara e executa a consulta SQL para buscar as conversas
$stmt = $con->prepare("
    SELECT
        u.UserID, u.Username, u.avatar, m.msg AS last_message, lc.max_ts AS last_timestamp
    FROM
        (SELECT
            CASE WHEN outgoing_msg_id = :user_id THEN incoming_msg_id ELSE outgoing_msg_id END AS other_user_id,
            MAX(timestamp) AS max_ts
        FROM messages
        WHERE outgoing_msg_id = :user_id OR incoming_msg_id = :user_id
        GROUP BY other_user_id
        ) AS lc
    INNER JOIN messages AS m ON m.timestamp = lc.max_ts AND ((m.outgoing_msg_id = :user_id AND m.incoming_msg_id = lc.other_user_id) OR (m.incoming_msg_id = :user_id AND m.outgoing_msg_id = lc.other_user_id))
    INNER JOIN users AS u ON u.UserID = lc.other_user_id
    ORDER BY lc.max_ts DESC
");

$stmt->execute(['user_id' => $current_user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1 class="text-center">Minhas Conversas</h1>
    <div class="chat-list">
        <?php if (empty($chats)): ?>
            <div class="alert alert-info text-center" style="margin: 15px;">Você ainda não tem nenhuma conversa.</div>
        <?php else: ?>
            <?php foreach ($chats as $chat): ?>
                <a href="chat.php?receiver_id=<?php echo $chat['UserID']; ?>" class="chat-item">
                    <div class="chat-item-avatar">
                        <?php 
                            if (empty($chat['avatar'])) {
                                echo "<img src='admin/uploads/default.png' alt='Avatar' />";
                            } else {
                                echo "<img src='admin/uploads/avatars/" . $chat['avatar'] . "' alt='Avatar' />";
                            }
                        ?>
                    </div>
                    <div class="chat-item-details">
                        <div class="chat-item-header">
                            <strong><?php echo htmlspecialchars($chat['Username']); ?></strong>
                            <small><?php echo date('d/m/Y H:i', strtotime($chat['last_timestamp'])); ?></small>
                        </div>
                        <p class="last-message">
                            <?php 
                                $last_message = htmlspecialchars($chat['last_message']);
                                if (strlen($last_message) > 50) {
                                    echo substr($last_message, 0, 50) . '...';
                                } else {
                                    echo $last_message;
                                }
                            ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>