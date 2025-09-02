<?php
// chat.php (versão com layout de duas colunas)
ob_start();
session_start();
$pageTitle = 'Chat';
include 'init.php';

// Redireciona se não estiver logado
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['uid'];

// --- 1. BUSCAR A LISTA DE CONVERSAS (PARA A BARRA LATERAL) ---
$stmt_list = $con->prepare("
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
$stmt_list->execute(['user_id' => $current_user_id]);
$chats = $stmt_list->fetchAll(PDO::FETCH_ASSOC);


// --- 2. DETERMINAR O CHAT ATIVO (PARA A JANELA PRINCIPAL) ---
$receiver_id = isset($_GET['receiver_id']) && is_numeric($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
$receiver_info = null;

// Se um ID de destinatário foi passado na URL, busca as informações dele
if ($receiver_id > 0) {
    $stmt_receiver = $con->prepare("SELECT UserID, Username FROM users WHERE UserID = ?");
    $stmt_receiver->execute([$receiver_id]);
    $receiver_info = $stmt_receiver->fetch(PDO::FETCH_ASSOC);
}
?>

<style>
    /* Cole aqui TODO o bloco de CSS do Passo 1 */
    .chat-container { display: flex; max-width: 1200px; margin: 30px auto; height: calc(100vh - 200px); border: 1px solid #ddd; border-radius: 5px; overflow: hidden; }
    .sidebar { width: 35%; border-right: 1px solid #ddd; display: flex; flex-direction: column; background-color: #f9f9f9; }
    .sidebar-header { padding: 15px; border-bottom: 1px solid #ddd; font-size: 1.2em; font-weight: bold; }
    .chat-list { overflow-y: auto; flex-grow: 1; }
    .chat-item { display: flex; align-items: center; padding: 15px; border-bottom: 1px solid #eee; text-decoration: none; color: #333; transition: background-color 0.2s; }
    .chat-item:hover { background-color: #f0f0f0; }
    .chat-item.active { background-color: #e6e6e6; }
    .chat-item-avatar img { width: 50px; height: 50px; border-radius: 50%; margin-right: 10px; }
    .chat-item-details { overflow: hidden; flex-grow: 1; }
    .chat-item-details strong { display: block; }
    .last-message { color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.9em; }
    .chat-window { width: 65%; display: flex; flex-direction: column; }
    .chat-header { padding: 15px; border-bottom: 1px solid #ddd; font-weight: bold; background-color: #f9f9f9; }
    #chat-box { flex-grow: 1; padding: 20px; overflow-y: auto; background-color: #fff; }
    #chat-form { display: flex; padding: 15px; border-top: 1px solid #ddd; background-color: #f9f9f9; }
    .message { margin-bottom: 15px; display: flex; flex-direction: column; }
    .message p { padding: 10px 15px; border-radius: 18px; margin: 0; max-width: 70%; word-wrap: break-word; }
    .sent { align-items: flex-end; }
    .sent p { background-color: #007bff; color: white; }
    .received { align-items: flex-start; }
    .received p { background-color: #e9e9eb; color: #333; }
    .msg-time { font-size: 0.75rem; color: #999; margin-top: 3px; }
</style>

<div class="container">
    <div class="chat-container">

        <aside class="sidebar">
            <div class="sidebar-header">Conversas</div>
            <div class="chat-list">
                <?php foreach ($chats as $chat): ?>
                    <a href="chat.php?receiver_id=<?php echo $chat['UserID']; ?>" class="chat-item <?php if($chat['UserID'] == $receiver_id) echo 'active'; ?>">
                        <div class="chat-item-avatar">
                            <img src="<?php echo empty($chat['avatar']) ? 'admin/uploads/default.png' : 'admin/uploads/avatars/' . $chat['avatar']; ?>" alt="Avatar">
                        </div>
                        <div class="chat-item-details">
                            <strong><?php echo htmlspecialchars($chat['Username']); ?></strong>
                            <p class="last-message"><?php echo htmlspecialchars($chat['last_message']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>

        <main class="chat-window">
            <?php if ($receiver_info): // Só mostra o chat se um destinatário válido foi selecionado ?>
                <div class="chat-header">
                    Conversando com <?php echo htmlspecialchars($receiver_info['Username']); ?>
                </div>
                <div id="chat-box">
                    </div>
                <form id="chat-form">
                    <input type="hidden" id="receiver-id-input" value="<?php echo $receiver_id; ?>">
                    <input type="text" id="message-input" class="form-control" placeholder="Digite sua mensagem..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary" style="margin-left:10px;">Enviar</button>
                </form>
            <?php else: // Mensagem padrão se nenhum chat estiver aberto ?>
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-center text-muted">
                        <h3>Selecione uma conversa para começar</h3>
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                </div>
            <?php endif; ?>
        </main>

    </div>
</div>

<?php if ($receiver_info): // Só inclui o JavaScript se um chat estiver ativo ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Código JavaScript para o chat (o mesmo que você já tinha)
    // ... (cole o SCRIPT do seu chat.php anterior aqui) ...
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const receiverId = document.getElementById('receiver-id-input').value;
    const currentUserId = <?php echo $current_user_id; ?>;

    async function fetchMessages() {
        try {
            const response = await fetch(`get_private_messages.php?receiver_id=${receiverId}`);
            const messages = await response.json();
            chatBox.innerHTML = '';
            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                const messageP = document.createElement('p');
                const timeSpan = document.createElement('span');
                messageP.textContent = msg.msg;
                timeSpan.textContent = new Date(msg.timestamp).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                timeSpan.className = 'msg-time';
                if (parseInt(msg.outgoing_msg_id) === currentUserId) {
                    messageDiv.className = 'message sent';
                } else {
                    messageDiv.className = 'message received';
                }
                messageDiv.appendChild(messageP);
                messageDiv.appendChild(timeSpan);
                chatBox.appendChild(messageDiv);
            });
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            console.error('Erro ao buscar mensagens:', error);
        }
    }

    async function sendMessage(event) {
        event.preventDefault();
        const message = messageInput.value.trim();
        if (message === '') return;
        const formData = new FormData();
        formData.append('message', message);
        formData.append('receiver_id', receiverId);
        try {
            await fetch('send_private_message.php', { method: 'POST', body: formData });
            messageInput.value = '';
            fetchMessages();
        } catch (error) {
            console.error('Erro ao enviar mensagem:', error);
        }
    }
    chatForm.addEventListener('submit', sendMessage);
    fetchMessages();
    setInterval(fetchMessages, 3000);
});
</script>
<?php endif; ?>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>