<?php
// send_private_message.php
session_start();
include 'init.php'; // Seu arquivo de conexão

// Responde como JSON
header('Content-Type: application/json');

// Garante que o usuário está logado e os dados foram enviados via POST
if (isset($_SESSION['uid']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $message      = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $sender_id    = $_SESSION['uid']; // ID de quem está enviando (usuário logado)
    $receiver_id  = filter_var($_POST['receiver_id'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($message) && !empty($receiver_id)) {
        $stmt = $con->prepare("INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) VALUES (?, ?, ?)");
        $stmt->execute([$sender_id, $receiver_id, $message]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Falha ao salvar a mensagem.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Mensagem ou destinatário vazio.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Não autorizado.']);
}
?>