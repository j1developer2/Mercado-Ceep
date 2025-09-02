<?php
// chat.php
ob_start();
session_start();
$pageTitle = 'Chat';
include 'init.php';

// Se o usuário não estiver logado, redireciona para o login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Pega o ID do destinatário pela URL e valida
$receiver_id = isset($_GET['receiver_id']) && is_numeric($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
if ($receiver_id == 0 || $receiver_id == $_SESSION['uid']) { // Não permite conversar consigo mesmo ou ID inválido
    echo '<div class="container"><div class="alert alert-danger">Usuário inválido.</div></div>';
    include $tpl . 'footer.php';
    exit();
}

// Busca informações do vendedor (destinatário)
$stmt = $con->prepare("SELECT Username FROM users WHERE UserID = ?");
$stmt->execute([$receiver_id]);
$receiver = $stmt->fetch();

if (!$receiver) {
    echo '<div class="container"><div class="alert alert-danger">Usuário não encontrado.</div></div>';
    include $tpl . 'footer.php';
    exit();
}

$current_user_id = $_SESSION['uid']; // ID do usuário logado
?>

<style>
    #chat-box { height: 500px; border: 1px solid #ddd; border-radius: 5px; overflow-y: scroll; padding: 15px; background-color: #f9f9f9; }
    .message { margin-bottom: 15px; display: flex; flex-direction: column; }
    .message p { padding: 10px 15px; border-radius: 18px; margin: 0; max-width: 70%; word-wrap: break-word; }
    .sent { align-items: flex-end; }
    .sent p { background-color: #007bff; color: white; }
    .received { align-items: flex-start; }
    .received p { background-color: #e9e9eb; color: #333; }
    .msg-time { font-size: 0.75rem; color: #999; margin-top: 3px; }
    .sent .msg-time { text-align: right; }
    .received .msg-time { text-align: left; }
    #chat-form { margin-top: 20px; display: flex; }
    #message-input { flex-grow: 1; margin-right: 10px; }
</style>

<div class="container">
    <h1 class="text-center">Conversa com <?php echo $receiver['Username']; ?></h1>

    <div id="chat-box"></div>

    <form id="chat-form">
        <input type="hidden" id="receiver-id-input" value="<?php echo $receiver_id; ?>">
        <input type="text" id="message-input" class="form-control" placeholder="Digite sua mensagem..." autocomplete="off" required>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const receiverId = document.getElementById('receiver-id-input').value;
    const currentUserId = <?php echo $current_user_id; ?>;

// Função para buscar e exibir as mensagens
async function fetchMessages() {
    // console.log('Buscando mensagens...'); // Descomente para ver se a função está sendo chamada
    try {
        const response = await fetch(`get_private_messages.php?receiver_id=${receiverId}`);
        
        // Verifique se a resposta da rede foi bem-sucedida
        if (!response.ok) {
            console.error('Erro de rede:', response.status, response.statusText);
            return;
        }

        const messages = await response.json();

        // LOG DE DEBUG: Veja o que o JavaScript recebeu
        console.log('Dados recebidos do servidor:', messages);

        // Verifique se a resposta tem um erro vindo do nosso PHP
        if (messages.error) {
            console.error('Erro do servidor:', messages.error);
            chatBox.innerHTML = `<div class="alert alert-warning">${messages.error}</div>`;
            return;
        }
        
        // Verifique se 'messages' é um array antes de tentar iterar
        if (!Array.isArray(messages)) {
            console.error('A resposta do servidor não é um array:', messages);
            return;
        }

        chatBox.innerHTML = ''; // Limpa antes de popular

        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            const messageP = document.createElement('p');
            const timeSpan = document.createElement('span');

            messageP.textContent = msg.msg;
            timeSpan.textContent = new Date(msg.timestamp).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            timeSpan.className = 'msg-time';

            // ATENÇÃO AQUI: Verifique se a comparação está correta
            if (parseInt(msg.outgoing_msg_id, 10) === currentUserId) {
                messageDiv.className = 'message sent';
            } else {
                messageDiv.className = 'message received';
            }
            
            messageDiv.appendChild(messageP);
            messageDiv.appendChild(timeSpan);
            chatBox.appendChild(messageDiv);
        });

        // Auto-scroll apenas se houver novas mensagens para evitar "pulos"
        if (messages.length > 0) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

    } catch (error) {
        console.error('Erro fatal ao processar mensagens:', error);
        chatBox.innerHTML = '<div class="alert alert-danger">Não foi possível carregar o chat. Verifique o console para mais detalhes.</div>';
    }
}

    // Função para enviar uma nova mensagem
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
            fetchMessages(); // Atualiza o chat com a nova mensagem
        } catch (error) {
            console.error('Erro ao enviar mensagem:', error);
        }
    }

    chatForm.addEventListener('submit', sendMessage);

    // Carrega o chat ao abrir a página
    fetchMessages();
    // Atualiza o chat a cada 3 segundos
    setInterval(fetchMessages, 3000);
});
</script>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>