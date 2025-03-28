<?php
require_once 'server.php';
$connector = new Connector();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $sql = "UPDATE messages SET viewed_status = 1 WHERE message_id = ?";
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute([$_POST['message_id']]);
}
?>