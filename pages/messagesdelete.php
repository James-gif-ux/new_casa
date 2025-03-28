<?php
require_once '../model/server.php';

if (isset($_GET['message_id']) && isset($_GET['action'])) {
    $message_id = $_GET['message_id'];
    $action = $_GET['action'];
    $connector = new Connector();

    if ($action === 'approve') {
        $sql = "UPDATE messages SET status = 'approved' WHERE message_id = :message_id";
    } elseif ($action === 'delete') {
        $sql = "DELETE FROM messages WHERE message_id = :message_id";
    }

    $params = [':message_id' => $message_id];

    if ($connector->executeUpdate($sql, $params)) {
        header("Location: ../views/messages.php?approved=true");
    } else {
        header("Location: ../views/messages.php?approved=false");
    }
    exit();
}
?>