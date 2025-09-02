<?php
// get_private_messages.php

session_start();

// Inclui APENAS a conexão com o banco, sem o HTML do cabeçalho.
include 'admin/connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    http_response_code(401); 
    echo json_encode(['error' => 'Não autenticado.']);
    exit();
}

if (!isset($_GET['receiver_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Destinatário não especificado.']);
    exit();
}

$sender_id = $_SESSION['uid'];
$receiver_id = intval($_GET['receiver_id']);

$stmt = $con->prepare("SELECT * FROM messages 
                       WHERE (outgoing_msg_id = :sender AND incoming_msg_id = :receiver)
                       OR (outgoing_msg_id = :receiver AND incoming_msg_id = :sender)
                       ORDER BY timestamp ASC");

$stmt->execute([
    'sender'   => $sender_id,
    'receiver' => $receiver_id
]);

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);

?>