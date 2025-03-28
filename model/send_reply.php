<?php
require_once 'server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connector = new Connector();
    
    $message_id = $_POST['message_id'];
    $reply_content = $_POST['reply_content'];
    $recipient_email = $_POST['recipient_email'];
    
    // Insert reply into database
    $sql = "INSERT INTO replies (message_id, reply_content, date_sent) 
            VALUES (?, ?, NOW())";
    
    $stmt = $connector->getConnection()->prepare($sql);
    $stmt->execute([$message_id, $reply_content]);
    
    // Send email notification (optional)
    mail($recipient_email, "Reply to your inquiry", $reply_content);
    
    echo json_encode(['success' => true]);
}
?>