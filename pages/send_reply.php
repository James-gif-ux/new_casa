<?php
require_once '../model/server.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connector = new Connector();
    
    $message_id = $_POST['message_id'];
    $reply_content = $_POST['reply_content'];
    $recipient_email = $_POST['recipient_email'];
    
    // Insert reply into database
    $sql = "INSERT INTO replies (message_id, reply_content, date_sent) 
            VALUES (:message_id, :reply_content, NOW())";
    
    $params = [
        ':message_id' => $message_id,
        ':reply_content' => $reply_content
    ];

    if ($connector->executeUpdate($sql, $params)) {
        // Update message status to replied
        $update_sql = "UPDATE messages SET status = 'replied' WHERE message_id = :message_id";
        $connector->executeUpdate($update_sql, [':message_id' => $message_id]);
        
        // Send email notification
        $to = $recipient_email;
        $subject = "Reply to your inquiry";
        $headers = "From: admin@casa.com";
        
        mail($to, $subject, $reply_content, $headers);
        
        header("Location: ../views/admin_inquires.php?reply=success");
    } else {
        header("Location: ../views/admin_inquires.php?reply=failed");
    }
    exit();
}
?>